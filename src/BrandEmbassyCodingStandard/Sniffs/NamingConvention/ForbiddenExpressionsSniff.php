<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\NamingConvention;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\SuppressHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function stripos;
use const T_CLASS;
use const T_CONSTANT_ENCAPSED_STRING;
use const T_DOC_COMMENT_STRING;
use const T_STRING;
use const T_VARIABLE;

final class ForbiddenExpressionsSniff implements Sniff
{
    private const NAME = 'BrandEmbassyCodingStandard.NamingConvention.ForbiddenExpressions';
    public const FORBIDDEN_EXPRESSION = 'forbiddenExpression';

    private static $forbiddenExpressions = [
        'dummy' => 'Use fake or test instead',
    ];


    public function register(): array
    {
        return [T_CLASS];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $pointer
     */
    public function process(File $phpcsFile, $pointer)
    {
        if (SuppressHelper::isSniffSuppressed($phpcsFile, $pointer, self::NAME) === true) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $tokenTypes = [T_STRING, T_VARIABLE, T_CONSTANT_ENCAPSED_STRING, T_DOC_COMMENT_STRING];

        $stringPointers = TokenHelper::findNextAll($phpcsFile, $tokenTypes, 0);

        foreach ($stringPointers as $stringPointer) {
            $string = $tokens[$stringPointer]['content'];

            $this->checkForbiddenExpression($phpcsFile, $stringPointer, $string);
        }
    }


    private function checkForbiddenExpression(File $phpcsFile, int $pointer, string $expression): void
    {
        foreach (self::$forbiddenExpressions as $forbiddenExpression => $replaceAdvice) {
            if (stripos($expression, $forbiddenExpression) !== false) {
                $phpcsFile->addError($replaceAdvice, $pointer, self::FORBIDDEN_EXPRESSION);
            }
        }
    }
}
