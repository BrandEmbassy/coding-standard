<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use const T_THROW;

class BlankLineBeforeThrowSniff implements Sniff
{
    public const CODE_NO_BLANK_LINE_BEFORE_THROW = 'NoBlankLineBeforeThrow';


    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_THROW];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $pointer
     */
    public function process(File $phpcsFile, $pointer): void
    {
        $tokens = $phpcsFile->getTokens();
        $current = $pointer;
        $previousLine = $tokens[$pointer]['line'] - 1;
        $prevLineTokens = [];

        while ($current >= 0 && $tokens[$current]['line'] >= $previousLine) {
            if ($tokens[$current]['line'] === $previousLine
                && $tokens[$current]['type'] !== 'T_WHITESPACE'
                && $tokens[$current]['type'] !== 'T_COMMENT'
            ) {
                $prevLineTokens[] = $tokens[$current]['type'];
            }

            --$current;
        }

        if (isset($prevLineTokens[0]) && ($prevLineTokens[0] === 'T_OPEN_CURLY_BRACKET' || $prevLineTokens[0] === 'T_COLON')) {
            return;
        }

        if ($prevLineTokens !== []) {
            $fix = $phpcsFile->addFixableError(
                'Missing blank line before throw statement',
                $pointer,
                self::CODE_NO_BLANK_LINE_BEFORE_THROW,
            );

            if ($fix) {
                $phpcsFile->fixer->addNewlineBefore($pointer);
            }
        }
    }
}
