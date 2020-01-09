<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function count;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_OPEN_CURLY_BRACKET;
use const T_TRAIT;
use const T_WHITESPACE;

class TraitUsePositionSniff implements Sniff
{
    /**
     * @return int[]|string[]
     */
    public function register(): array
    {
        return [
            T_CLASS,
            T_ANON_CLASS,
            T_TRAIT,
        ];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $classPointer
     */
    public function process(File $phpcsFile, $classPointer): void
    {
        $usePointers = ClassHelper::getTraitUsePointers($phpcsFile, $classPointer);

        if (count($usePointers) === 0) {
            return;
        }

        $this->checkFirstInClass($phpcsFile, $usePointers[0]);
    }


    private function checkFirstInClass(File $phpcsFile, int $firstUsePointer): void
    {
        $tokens = $phpcsFile->getTokens();

        /** @var int $pointerBeforeFirstUse */
        $pointerBeforeFirstUse = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $firstUsePointer - 1);

        if ($tokens[$pointerBeforeFirstUse]['code'] !== T_OPEN_CURLY_BRACKET) {
            $phpcsFile->addErrorOnLine(
                'Trait is not first in class',
                $tokens[$firstUsePointer]['line'],
                'be-trait-position'
            );
        }
    }
}
