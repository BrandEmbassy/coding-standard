<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\NestedArrayMultiline;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use function assert;
use function is_string;
use function str_repeat;
use function strlen;
use const T_COMMA;
use const T_OPEN_CURLY_BRACKET;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_SHORT_ARRAY;
use const T_WHITESPACE;

class NestedArrayMultilineSniff implements Sniff
{
    public const CODE_NESTED_NOT_MULTILINE = 'NestedNotMultiline';

    private const INDENT_SIZE = 4;


    /**
     * @return list<int|string>
     */
    public function register(): array
    {
        return [T_OPEN_SHORT_ARRAY];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();
        $opener = $stackPtr;

        /** @var int $closer */
        $closer = $tokens[$opener]['bracket_closer'];

        if ($this->isEmpty($phpcsFile, $opener, $closer)) {
            return;
        }

        $isMultiline = $tokens[$opener]['line'] !== $tokens[$closer]['line'];

        if ($isMultiline) {
            return;
        }

        if (!$this->containsNestedArray($phpcsFile, $opener, $closer)) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'Array containing nested array must be multiline',
            $opener,
            self::CODE_NESTED_NOT_MULTILINE,
        );

        if ($fix) {
            $this->fixToMultiline($phpcsFile, $opener, $closer);
        }
    }


    private function isEmpty(File $phpcsFile, int $opener, int $closer): bool
    {
        $tokens = $phpcsFile->getTokens();

        if ($closer - $opener === 1) {
            return true;
        }

        for ($i = $opener + 1; $i < $closer; $i++) {
            if ($tokens[$i]['code'] !== T_WHITESPACE) {
                return false;
            }
        }

        return true;
    }


    private function containsNestedArray(File $phpcsFile, int $opener, int $closer): bool
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $opener + 1; $i < $closer; $i++) {
            if ($tokens[$i]['code'] === T_OPEN_SHORT_ARRAY) {
                return true;
            }
        }

        return false;
    }


    private function hasTrailingComma(File $phpcsFile, int $opener, int $closer): bool
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $closer - 1; $i > $opener; $i--) {
            if ($tokens[$i]['code'] === T_WHITESPACE) {
                continue;
            }

            return $tokens[$i]['code'] === T_COMMA;
        }

        return false;
    }


    private function fixToMultiline(File $phpcsFile, int $opener, int $closer): void
    {
        $tokens = $phpcsFile->getTokens();
        $phpcsFile->fixer->beginChangeset();

        $baseIndent = $this->getIndentLevel($phpcsFile, $opener);
        $elementIndent = str_repeat(' ', $baseIndent + self::INDENT_SIZE);
        $closerIndent = str_repeat(' ', $baseIndent);

        $phpcsFile->fixer->addContent($opener, "\n" . $elementIndent);

        $this->removeWhitespaceAfter($phpcsFile, $opener, $closer);

        $lastCommaPointer = null;

        for ($i = $opener + 1; $i < $closer; $i++) {
            $code = $tokens[$i]['code'];

            if ($code === T_OPEN_SHORT_ARRAY) {
                /** @var int $nestedCloser */
                $nestedCloser = $tokens[$i]['bracket_closer'];
                $i = $nestedCloser;
                continue;
            }

            if ($code === T_OPEN_PARENTHESIS) {
                /** @var int $parenCloser */
                $parenCloser = $tokens[$i]['parenthesis_closer'];
                $i = $parenCloser;
                continue;
            }

            if ($code === T_OPEN_CURLY_BRACKET) {
                /** @var int $curlyCloser */
                $curlyCloser = $tokens[$i]['bracket_closer'];
                $i = $curlyCloser;
                continue;
            }

            if ($code === T_COMMA) {
                $lastCommaPointer = $i;

                $this->removeWhitespaceAfter($phpcsFile, $i, $closer);

                $phpcsFile->fixer->addContent($i, "\n" . $elementIndent);
            }
        }

        if (!$this->hasTrailingComma($phpcsFile, $opener, $closer)) {
            $searchFrom = $lastCommaPointer !== null
                ? $lastCommaPointer + 1
                : $opener + 1;
            $lastElementEnd = $this->findLastNonWhitespace($phpcsFile, $searchFrom, $closer);

            if ($lastElementEnd !== null) {
                $phpcsFile->fixer->addContent($lastElementEnd, ',');
            }
        }

        $this->removeWhitespaceBefore($phpcsFile, $opener, $closer);

        $phpcsFile->fixer->addContentBefore($closer, "\n" . $closerIndent);

        $phpcsFile->fixer->endChangeset();
    }


    private function getIndentLevel(File $phpcsFile, int $stackPtr): int
    {
        $tokens = $phpcsFile->getTokens();
        $line = $tokens[$stackPtr]['line'];
        $firstOnLine = $stackPtr;

        for ($i = $stackPtr - 1; $i >= 0; $i--) {
            if ($tokens[$i]['line'] !== $line) {
                $firstOnLine = $i + 1;
                break;
            }

            if ($i === 0) {
                $firstOnLine = 0;
            }
        }

        if ($tokens[$firstOnLine]['code'] === T_WHITESPACE) {
            $content = $tokens[$firstOnLine]['content'];
            assert(is_string($content));

            return strlen($content);
        }

        return 0;
    }


    private function removeWhitespaceAfter(File $phpcsFile, int $position, int $closer): void
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $position + 1; $i < $closer; $i++) {
            if ($tokens[$i]['code'] !== T_WHITESPACE) {
                break;
            }

            $phpcsFile->fixer->replaceToken($i, '');
        }
    }


    private function removeWhitespaceBefore(File $phpcsFile, int $opener, int $closer): void
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $closer - 1; $i > $opener; $i--) {
            if ($tokens[$i]['code'] !== T_WHITESPACE) {
                break;
            }

            $phpcsFile->fixer->replaceToken($i, '');
        }
    }


    private function findLastNonWhitespace(File $phpcsFile, int $start, int $end): ?int
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $end - 1; $i >= $start; $i--) {
            if ($tokens[$i]['code'] !== T_WHITESPACE) {
                return $i;
            }
        }

        return null;
    }
}
