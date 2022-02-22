<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;

/**
 * @final
 */
class FinalClassByAnnotationSniff implements Sniff
{
    public const CODE_FINAL_CLASS_BY_KEYWORD = 'FinalClassByKeyword';


    public function register(): array
    {
        return [T_FINAL];
    }


    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $nextEffectiveTokenPointer = TokenHelper::findNextEffective($phpcsFile, $stackPtr + 1);
        if ($tokens[$nextEffectiveTokenPointer]['code'] !== T_CLASS) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'Found final class with "final" keyword, use @final annotation instead.',
            $stackPtr,
            self::CODE_FINAL_CLASS_BY_KEYWORD
        );
        if (!$fix) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->replaceToken($stackPtr, '');
        if ($tokens[$stackPtr + 1]['code'] === T_WHITESPACE) {
            $phpcsFile->fixer->replaceToken($stackPtr + 1, '');
        }
        $phpcsFile->fixer->addContentBefore($stackPtr, "/**\n * @final\n */\n");
        $phpcsFile->fixer->endChangeset();
    }
}
