<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\PropertyHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function count;
use function reset;
use function sprintf;
use function substr_count;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_CLOSE_CURLY_BRACKET;
use const T_CONST;
use const T_OPEN_CURLY_BRACKET;
use const T_SEMICOLON;
use const T_TRAIT;
use const T_VARIABLE;
use const T_WHITESPACE;

final class TraitUseSpacingSniff implements Sniff
{
    public const CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE = 'IncorrectLinesCountBeforeFirstUse';
    public const CODE_INCORRECT_LINES_COUNT_BETWEEN_USES = 'IncorrectLinesCountBetweenUses';
    public const CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE = 'IncorrectLinesCountAfterLastUse';

    public int $linesBeforeFollowingPropertyOrConstant;

    public int $linesCountBeforeFirstUse = 1;

    public ?int $linesCountBeforeFirstUseWhenFirstInClass = null;

    public int $linesCountBetweenUses = 0;

    public int $linesCountAfterLastUse = 1;

    public int $linesCountAfterLastUseWhenLastInClass = 1;


    /**
     * @return array<int, (int|string)>
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

        $this->checkLinesBeforeFirstUse($phpcsFile, $usePointers[0]);
        $this->checkLinesAfterLastUse($phpcsFile, $usePointers[count($usePointers) - 1]);
        $this->checkLinesBetweenUses($phpcsFile, $usePointers);
    }


    private function checkLinesBeforeFirstUse(File $phpcsFile, int $firstUsePointer): void
    {
        $tokens = $phpcsFile->getTokens();

        /** @var int $pointerBeforeFirstUse */
        $pointerBeforeFirstUse = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $firstUsePointer - 1);
        $isAtTheStartOfClass = $tokens[$pointerBeforeFirstUse]['code'] === T_OPEN_CURLY_BRACKET;

        $whitespaceBeforeFirstUse = '';

        if ($pointerBeforeFirstUse + 1 !== $firstUsePointer) {
            $whitespaceBeforeFirstUse .= TokenHelper::getContent(
                $phpcsFile,
                $pointerBeforeFirstUse + 1,
                $firstUsePointer - 1
            );
        }

        $requiredLinesCountBeforeFirstUse = SniffSettingsHelper::normalizeInteger($this->linesCountBeforeFirstUse);
        if ($isAtTheStartOfClass
            && $this->linesCountBeforeFirstUseWhenFirstInClass !== null
        ) {
            $requiredLinesCountBeforeFirstUse = SniffSettingsHelper::normalizeInteger(
                $this->linesCountBeforeFirstUseWhenFirstInClass
            );
        }
        $actualLinesCountBeforeFirstUse = substr_count($whitespaceBeforeFirstUse, $phpcsFile->eolChar) - 1;

        if ($actualLinesCountBeforeFirstUse === $requiredLinesCountBeforeFirstUse) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            sprintf(
                'Expected %d lines before first use statement, found %d.',
                $requiredLinesCountBeforeFirstUse,
                $actualLinesCountBeforeFirstUse
            ),
            $firstUsePointer,
            self::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE
        );

        if (!$fix) {
            return;
        }

        $pointerBeforeIndentation = TokenHelper::findPreviousContent(
            $phpcsFile,
            T_WHITESPACE,
            $phpcsFile->eolChar,
            $firstUsePointer,
            $pointerBeforeFirstUse
        );

        $phpcsFile->fixer->beginChangeset();

        if ($pointerBeforeIndentation !== null) {
            for ($i = $pointerBeforeFirstUse + 1; $i <= $pointerBeforeIndentation; $i++) {
                $phpcsFile->fixer->replaceToken($i, '');
            }
        }
        for ($i = 0; $i <= $requiredLinesCountBeforeFirstUse; $i++) {
            $phpcsFile->fixer->addNewline($pointerBeforeFirstUse);
        }

        $phpcsFile->fixer->endChangeset();
    }


    private function checkLinesAfterLastUse(File $phpcsFile, int $lastUsePointer): void
    {
        $tokens = $phpcsFile->getTokens();

        /** @var int $lastUseEndPointer */
        $lastUseEndPointer = TokenHelper::findNextLocal(
            $phpcsFile,
            [T_SEMICOLON, T_OPEN_CURLY_BRACKET],
            $lastUsePointer + 1
        );
        if ($tokens[$lastUseEndPointer]['code'] === T_OPEN_CURLY_BRACKET) {
            /** @var int $lastUseEndPointer */
            $lastUseEndPointer = $tokens[$lastUseEndPointer]['bracket_closer'];
        }

        /** @var int $nextWhitespacePointer */
        $nextWhitespacePointer = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $lastUseEndPointer + 1);
        $whitespaceEnd = $nextWhitespacePointer - 1;
        if ($lastUseEndPointer !== $whitespaceEnd && $tokens[$whitespaceEnd]['content'] !== $phpcsFile->eolChar) {
            $lastEolPointer = TokenHelper::findPreviousContent(
                $phpcsFile,
                T_WHITESPACE,
                $phpcsFile->eolChar,
                $whitespaceEnd - 1,
                $lastUseEndPointer
            );
            $whitespaceEnd = $lastEolPointer ?? $lastUseEndPointer;
        }
        $whitespaceAfterLastUse = TokenHelper::getContent($phpcsFile, $lastUseEndPointer + 1, $whitespaceEnd);

        $requiredLinesCountAfterLastUse = $this->getRequiredLinesCountAfterLastUse(
            $phpcsFile,
            $tokens,
            $lastUseEndPointer
        );

        $actualLinesCountAfterLastUse = substr_count($whitespaceAfterLastUse, $phpcsFile->eolChar) - 1;

        if ($actualLinesCountAfterLastUse === $requiredLinesCountAfterLastUse) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            sprintf(
                'Expected %d lines after last use statement, found %d.',
                $requiredLinesCountAfterLastUse,
                $actualLinesCountAfterLastUse
            ),
            $lastUsePointer,
            self::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE
        );

        if (!$fix) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();
        for ($i = $lastUseEndPointer + 1; $i <= $whitespaceEnd; $i++) {
            $phpcsFile->fixer->replaceToken($i, '');
        }
        for ($i = 0; $i <= $requiredLinesCountAfterLastUse; $i++) {
            $phpcsFile->fixer->addNewline($lastUseEndPointer);
        }
        $phpcsFile->fixer->endChangeset();
    }


    /**
     * @param array<int, mixed[]> $tokens
     */
    private function getRequiredLinesCountAfterLastUse(File $phpcsFile, array $tokens, int $lastUseEndPointer): int
    {
        $pointerAfterLastUse = TokenHelper::findNextEffective($phpcsFile, $lastUseEndPointer + 1);
        $isAtTheEndOfClass = $tokens[$pointerAfterLastUse]['code'] === T_CLOSE_CURLY_BRACKET;

        if ($isAtTheEndOfClass) {
            return SniffSettingsHelper::normalizeInteger($this->linesCountAfterLastUseWhenLastInClass);
        }

        $followingPointers = TokenHelper::findNextAll($phpcsFile, [T_VARIABLE, T_CONST], $lastUseEndPointer);

        if ($followingPointers === []) {
            return SniffSettingsHelper::normalizeInteger($this->linesCountAfterLastUse);
        }

        if ($tokens[reset($followingPointers)]['code'] === T_CONST) {
            return SniffSettingsHelper::normalizeInteger($this->linesBeforeFollowingPropertyOrConstant);
        }

        if (PropertyHelper::isProperty($phpcsFile, reset($followingPointers))) {
            return SniffSettingsHelper::normalizeInteger($this->linesBeforeFollowingPropertyOrConstant);
        }

        return SniffSettingsHelper::normalizeInteger($this->linesCountAfterLastUse);
    }


    /**
     * @param int[] $usePointers
     */
    private function checkLinesBetweenUses(File $phpcsFile, array $usePointers): void
    {
        if (count($usePointers) === 1) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $requiredLinesCountBetweenUses = SniffSettingsHelper::normalizeInteger($this->linesCountBetweenUses);

        $previousUsePointer = null;
        foreach ($usePointers as $usePointer) {
            if ($previousUsePointer === null) {
                $previousUsePointer = $usePointer;
                continue;
            }

            /** @var int $previousUseEndPointer */
            $previousUseEndPointer = TokenHelper::findNextLocal(
                $phpcsFile,
                [T_SEMICOLON, T_OPEN_CURLY_BRACKET],
                $previousUsePointer + 1
            );
            if ($tokens[$previousUseEndPointer]['code'] === T_OPEN_CURLY_BRACKET) {
                /** @var int $previousUseEndPointer */
                $previousUseEndPointer = $tokens[$previousUseEndPointer]['bracket_closer'];
            }

            $actualLinesCountAfterPreviousUse = $tokens[$usePointer]['line'] - $tokens[$previousUseEndPointer]['line'] - 1;

            if ($actualLinesCountAfterPreviousUse === $requiredLinesCountBetweenUses) {
                $previousUsePointer = $usePointer;
                continue;
            }

            $errorParameters = [
                sprintf(
                    'Expected %d lines between same types of use statement, found %d.',
                    $requiredLinesCountBetweenUses,
                    $actualLinesCountAfterPreviousUse
                ),
                $usePointer,
                self::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES,
            ];

            $pointerBeforeUse = TokenHelper::findPreviousEffective($phpcsFile, $usePointer - 1);

            if ($previousUseEndPointer !== $pointerBeforeUse) {
                $phpcsFile->addError(...$errorParameters);
                $previousUsePointer = $usePointer;
                continue;
            }

            $fix = $phpcsFile->addFixableError(...$errorParameters);

            if (!$fix) {
                $previousUsePointer = $usePointer;
                continue;
            }

            $pointerBeforeIndentation = TokenHelper::findPreviousContent(
                $phpcsFile,
                T_WHITESPACE,
                $phpcsFile->eolChar,
                $usePointer,
                $previousUseEndPointer
            );

            $phpcsFile->fixer->beginChangeset();
            if ($pointerBeforeIndentation !== null) {
                for ($i = $previousUseEndPointer + 1; $i <= $pointerBeforeIndentation; $i++) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }
            }
            for ($i = 0; $i <= $requiredLinesCountBetweenUses; $i++) {
                $phpcsFile->fixer->addNewline($previousUseEndPointer);
            }
            $phpcsFile->fixer->endChangeset();

            $previousUsePointer = $usePointer;
        }
    }
}
