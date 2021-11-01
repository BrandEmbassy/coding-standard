<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FunctionCommentSniff as BaseFunctionCommentSniff;
use SlevomatCodingStandard\Helpers\SuppressHelper;

/**
 * Decorates Squiz.Commenting.FunctionComment to allow skipping via annotation.
 */
class FunctionCommentSniff implements Sniff
{
    private const NAME = 'BrandEmbassyCodingStandard.Commenting.FunctionComment';

    private BaseFunctionCommentSniff $decoratedSniff;


    public function __construct()
    {
        $this->decoratedSniff = new BaseFunctionCommentSniff();
    }


    /**
     * @return int[]
     */
    public function register(): array
    {
        return $this->decoratedSniff->register();
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $pointer
     */
    public function process(File $phpcsFile, $pointer): void
    {
        if (SuppressHelper::isSniffSuppressed($phpcsFile, $pointer, self::NAME) === true) {
            return;
        }

        $this->decoratedSniff->process($phpcsFile, $pointer);
    }
}
