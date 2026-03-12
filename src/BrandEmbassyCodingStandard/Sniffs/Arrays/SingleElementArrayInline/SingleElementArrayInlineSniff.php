<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\SingleElementArrayInline;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use function assert;
use function is_int;
use function is_string;
use function str_contains;
use const T_COMMA;
use const T_OPEN_CURLY_BRACKET;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_SHORT_ARRAY;
use const T_WHITESPACE;

class SingleElementArrayInlineSniff implements Sniff
{
    public const CODE_SINGLE_ELEMENT_NOT_INLINE = 'SingleElementNotInline';


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

        $elementCount = $this->countElements($phpcsFile, $opener, $closer);

        if ($elementCount !== 1) {
            return;
        }

        $isMultiline = $tokens[$opener]['line'] !== $tokens[$closer]['line'];

        if (!$isMultiline) {
            return;
        }

        if ($this->containsNestedArray($phpcsFile, $opener, $closer)) {
            return;
        }

        if ($this->elementContentIsMultiline($phpcsFile, $opener, $closer)) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'Single-element array must be inline',
            $opener,
            self::CODE_SINGLE_ELEMENT_NOT_INLINE,
        );

        if ($fix) {
            $this->fixToInline($phpcsFile, $opener, $closer);
        }
    }


    private function countElements(File $phpcsFile, int $opener, int $closer): int
    {
        $tokens = $phpcsFile->getTokens();

        if ($closer - $opener === 1) {
            return 0;
        }

        $hasContent = false;

        for ($i = $opener + 1; $i < $closer; $i++) {
            if ($tokens[$i]['code'] !== T_WHITESPACE) {
                $hasContent = true;
                break;
            }
        }

        if (!$hasContent) {
            return 0;
        }

        $commaCount = 0;

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
                $commaCount++;
            }
        }

        if ($this->hasTrailingComma($phpcsFile, $opener, $closer)) {
            $commaCount--;
        }

        return $commaCount + 1;
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


    private function elementContentIsMultiline(File $phpcsFile, int $opener, int $closer): bool
    {
        $tokens = $phpcsFile->getTokens();

        $firstContent = null;
        $lastContent = null;

        for ($i = $opener + 1; $i < $closer; $i++) {
            if ($tokens[$i]['code'] !== T_WHITESPACE) {
                if ($firstContent === null) {
                    $firstContent = $i;
                }

                $lastContent = $i;
            }
        }

        if ($firstContent === null || $lastContent === null) {
            return false;
        }

        // Exclude trailing comma from the check
        if ($tokens[$lastContent]['code'] === T_COMMA) {
            for ($i = $lastContent - 1; $i > $opener; $i--) {
                if ($tokens[$i]['code'] !== T_WHITESPACE) {
                    $lastContent = $i;
                    break;
                }
            }
        }

        return $tokens[$firstContent]['line'] !== $tokens[$lastContent]['line'];
    }


    private function fixToInline(File $phpcsFile, int $opener, int $closer): void
    {
        $tokens = $phpcsFile->getTokens();
        $phpcsFile->fixer->beginChangeset();

        $firstContent = null;
        $lastContent = null;
        $trailingCommaPos = null;

        for ($i = $opener + 1; $i < $closer; $i++) {
            if ($tokens[$i]['code'] !== T_WHITESPACE) {
                if ($firstContent === null) {
                    $firstContent = $i;
                }

                $lastContent = $i;
            }
        }

        if ($this->hasTrailingComma($phpcsFile, $opener, $closer)) {
            $trailingCommaPos = $lastContent;
            assert(is_int($trailingCommaPos));
            // Find the actual last content before the trailing comma
            for ($i = $trailingCommaPos - 1; $i > $opener; $i--) {
                if ($tokens[$i]['code'] !== T_WHITESPACE) {
                    $lastContent = $i;
                    break;
                }
            }
        }

        for ($i = $opener + 1; $i < $closer; $i++) {
            // Remove trailing comma
            if ($trailingCommaPos !== null && $i === $trailingCommaPos) {
                $phpcsFile->fixer->replaceToken($i, '');
                continue;
            }

            if ($tokens[$i]['code'] !== T_WHITESPACE) {
                continue;
            }

            // Remove all whitespace before first content and after last content
            if ($firstContent !== null && $lastContent !== null && ($i < $firstContent || $i > $lastContent)) {
                $phpcsFile->fixer->replaceToken($i, '');
                continue;
            }

            // For whitespace between content tokens, collapse newlines to space
            $content = $tokens[$i]['content'];
            assert(is_string($content));

            if (str_contains($content, "\n")) {
                $phpcsFile->fixer->replaceToken($i, ' ');
            }
        }

        $phpcsFile->fixer->endChangeset();
    }
}
