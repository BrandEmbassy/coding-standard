<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_CLASS;
use const T_FINAL;
use const T_WHITESPACE;

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


    /**
     * @param int $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        $nextEffectivePtr = TokenHelper::findNextEffective($phpcsFile, $stackPtr + 1);
        if ($tokens[$nextEffectivePtr]['code'] !== T_CLASS) {
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

        $this->addFinalAnnotation($phpcsFile, $stackPtr);

        $phpcsFile->fixer->endChangeset();
    }


    private function addFinalAnnotation(File $phpcsFile, int $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        $previousPtr = TokenHelper::findPreviousExcluding($phpcsFile, [T_WHITESPACE], $stackPtr - 1);
        if ($previousPtr === null || $tokens[$previousPtr]['code'] !== T_DOC_COMMENT_CLOSE_TAG) {
            $phpcsFile->fixer->replaceToken($stackPtr, "/**\n * @final\n */\n");
            return;
        }

        $phpcsFile->fixer->replaceToken($previousPtr, "*\n * @final\n */");
    }
}
