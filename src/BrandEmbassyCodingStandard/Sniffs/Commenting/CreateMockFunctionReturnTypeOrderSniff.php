<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function preg_match;
use const T_FUNCTION;

/**
 * @final
 */
class CreateMockFunctionReturnTypeOrderSniff implements Sniff
{
    public const CODE_CREATE_MOCK_FUNCTION_RETURN_TYPE_ORDER = 'CreateMockFunctionReturnTypeOrder';


    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_FUNCTION];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        $functionNamePtr = TokenHelper::findNextEffective($phpcsFile, $stackPtr + 1);

        if ($functionNamePtr === null) {
            return;
        }

        $phpDocCommentCloseTagPtr = TokenHelper::findFirstNonWhitespaceOnPreviousLine($phpcsFile, $stackPtr);

        if ($phpDocCommentCloseTagPtr === null) {
            return;
        }

        if ($tokens[$phpDocCommentCloseTagPtr]['code'] !== 'PHPCS_T_DOC_COMMENT_CLOSE_TAG') {
            return;
        }

        $phpDocCommentOpenTagPtr = $tokens[$phpDocCommentCloseTagPtr]['comment_opener'];

        if ($phpDocCommentOpenTagPtr === null) {
            return;
        }

        $returnTagPtr = TokenHelper::findNextContent(
            $phpcsFile,
            ['PHPCS_T_DOC_COMMENT_TAG'],
            '@return',
            $phpDocCommentOpenTagPtr,
            $phpDocCommentCloseTagPtr,
        );

        if ($returnTagPtr === null) {
            return;
        }

        $returnTypePtr = TokenHelper::findNextExcluding(
            $phpcsFile,
            ['PHPCS_T_DOC_COMMENT_WHITESPACE'],
            $returnTagPtr + 1,
            $phpDocCommentCloseTagPtr,
        );

        if ($returnTypePtr === null) {
            return;
        }

        $returnType = $tokens[$returnTypePtr]['content'];

        if (preg_match('~^MockInterface&(\w+)$~', $returnType, $matches) !== 1) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'Found incorrectly ordered "createMock" function annotation return types.',
            $returnTypePtr,
            self::CODE_CREATE_MOCK_FUNCTION_RETURN_TYPE_ORDER,
        );

        if (!$fix) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->replaceToken($returnTypePtr, $matches[1] . '&MockInterface');
        $phpcsFile->fixer->endChangeset();
    }
}
