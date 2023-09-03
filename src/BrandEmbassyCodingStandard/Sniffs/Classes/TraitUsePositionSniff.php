<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function end;
use function reset;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_OPEN_CURLY_BRACKET;
use const T_SEMICOLON;
use const T_TRAIT;
use const T_WHITESPACE;

class TraitUsePositionSniff implements Sniff
{
    public const CODE_TRAIT_USE_IS_NOT_FIRST_IN_CLASS = 'NotFirstInClass';


    /**
     * @return int[]|string[]
     */
    public function register(): array
    {
        return [T_CLASS, T_ANON_CLASS, T_TRAIT];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $classPointer
     */
    public function process(File $phpcsFile, $classPointer): void
    {
        $traitPointers = ClassHelper::getTraitUsePointers($phpcsFile, $classPointer);

        if ($traitPointers === []) {
            return;
        }

        $this->checkFirstInClass($phpcsFile, $classPointer, $traitPointers);
    }


    /**
     * @param int[] $traitPointers
     */
    private function checkFirstInClass(File $phpcsFile, int $classPointer, array $traitPointers): void
    {
        $tokens = $phpcsFile->getTokens();

        /** @var int $firstUsePointer */
        $firstUsePointer = reset($traitPointers);

        /** @var int $pointerBeforeFirstUse */
        $pointerBeforeFirstUse = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $firstUsePointer - 1);

        if ($tokens[$pointerBeforeFirstUse]['code'] !== T_OPEN_CURLY_BRACKET) {
            $fix = $phpcsFile->addFixableError(
                'Trait is not first in class',
                $tokens[$firstUsePointer]['line'],
                self::CODE_TRAIT_USE_IS_NOT_FIRST_IN_CLASS,
            );

            if ($fix) {
                $this->fixTraitsPosition($phpcsFile, $traitPointers, $classPointer);
            }
        }
    }


    /**
     * @param int[] $traitPointers
     */
    private function fixTraitsPosition(File $phpcsFile, array $traitPointers, int $classPointer): void
    {
        $phpcsFile->fixer->beginChangeset();

        /** @var int $firstTraitPointer */
        $firstTraitPointer = reset($traitPointers);

        /** @var int $lastTraitPointer */
        $lastTraitPointer = end($traitPointers);

        /** @var int $lastNonWhitespaceTokenPointer */
        $lastNonWhitespaceTokenPointer = TokenHelper::findPreviousExcluding(
            $phpcsFile,
            T_WHITESPACE,
            $firstTraitPointer - 1,
        );

        $startPointer = $lastNonWhitespaceTokenPointer + 1;
        $lastPointer = TokenHelper::findNext($phpcsFile, T_SEMICOLON, $lastTraitPointer);

        $traitsContent = TokenHelper::getContent($phpcsFile, $startPointer, $lastPointer);

        for ($i = $startPointer; $i <= $lastPointer; ++$i) {
            $phpcsFile->fixer->replaceToken($i, '');
        }

        /** @var int $bracketPointer */
        $bracketPointer = TokenHelper::findNext($phpcsFile, T_OPEN_CURLY_BRACKET, $classPointer);

        $phpcsFile->fixer->addContent($bracketPointer, $traitsContent);
        $phpcsFile->fixer->endChangeset();
    }
}
