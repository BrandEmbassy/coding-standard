<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\NamingConvention;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ScopeHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_filter;
use function array_search;
use function sprintf;
use const T_VARIABLE;

final class ForbiddenVariableNameSniff implements Sniff
{
    private const FORBIDDEN_VARIABLE_NAME = 'ForbiddenVariableName';

    /**
     * @var array<string, class-string>
     */
    public $forbiddenVariableNames = [];


    /**
     * @return string[]|int[]
     */
    public function register(): array
    {
        return [
            T_VARIABLE,
        ];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $variableName = $tokens[$stackPtr]['content'];

        if (!isset($this->forbiddenVariableNames[$variableName])) {
            return;
        }

        $variablesInScope = $this->getVariablesInTheSameScope($phpcsFile, $stackPtr);
        $replacement = $this->forbiddenVariableNames[$variableName];

        $variablesToReplace = [];
        foreach ($variablesInScope as $variablePointer) {
            if ($tokens[$variablePointer]['content'] === $variableName) {
                $variablesToReplace[] = $variablePointer;
            }
        }

        $errorMessage = sprintf(
            'Using %s variable name is forbidden.',
            array_search($replacement, $this->forbiddenVariableNames)
        );

        if ($this->variableWithSameNameAlreadyExists($tokens, $variablesInScope, $replacement)) {
            $phpcsFile->addError($errorMessage, $stackPtr, self::FORBIDDEN_VARIABLE_NAME);

            return;
        }

        foreach ($variablesToReplace as $variableToReplace) {
            $fix = $phpcsFile->addFixableError(
                $errorMessage,
                $variableToReplace,
                self::FORBIDDEN_VARIABLE_NAME
            );

            if (!$fix) {
                return;
            }

            $phpcsFile->fixer->beginChangeset();
            $phpcsFile->fixer->replaceToken($variableToReplace, $replacement);
            $phpcsFile->fixer->endChangeset();
        }
    }


    private function getVariablesInTheSameScope(File $phpcsFile, int $scopeStartPointer): array
    {
        $allVariables = TokenHelper::findNextAll($phpcsFile, [T_VARIABLE], $scopeStartPointer);

        return array_filter(
            $allVariables,
            static function (int $currentVariablePointer) use ($phpcsFile, $scopeStartPointer): bool {
                return ScopeHelper::isInSameScope($phpcsFile, $currentVariablePointer, $scopeStartPointer);
            }
        );
    }


    private function variableWithSameNameAlreadyExists(
        array $tokens,
        array $allVariablesInTheScope,
        string $replacement
    ): bool {
        foreach ($allVariablesInTheScope as $variablePointer) {
            if ($tokens[$variablePointer]['content'] === $replacement) {
                return true;
            }
        }

        return false;
    }
}
