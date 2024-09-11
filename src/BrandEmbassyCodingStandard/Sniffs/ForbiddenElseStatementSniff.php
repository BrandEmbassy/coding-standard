<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use function strtolower;
use const T_ELSE;
use const T_ELSEIF;

/**
 * @final
 */
class ForbiddenElseStatementSniff implements Sniff
{
    public const CODE_FORBIDDEN_ELSE_STATEMENT_SNIFF = 'ForbiddenElseStatement';


    /**
     * @return int[]|string[]
     */
    public function register(): array
    {
        return [
            T_ELSE,
            T_ELSEIF,
        ];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        $statement = strtolower($tokens[$stackPtr]['content']);

        $error = 'Use of ' . $statement . ' is forbidden. See: https://github.com/BrandEmbassy/developers-manifest/issues/365.';

        $phpcsFile->addError($error, $stackPtr, self::CODE_FORBIDDEN_ELSE_STATEMENT_SNIFF);
    }
}
