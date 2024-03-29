<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\NamingConvention;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\CamelCapsFunctionNameSniff as BaseCamelCapsFunctionNameSniff;
use SlevomatCodingStandard\Helpers\SuppressHelper;

/**
 * Decorates PSR1.Methods.CamelCapsMethodName to allow skipping via annotation.
 */
class CamelCapsFunctionNameSniff implements Sniff
{
    private const NAME = 'BrandEmbassyCodingStandard.NamingConvention.CamelCapsFunctionName';

    public bool $strict;

    private BaseCamelCapsFunctionNameSniff $decoratedSniff;


    public function __construct()
    {
        $this->decoratedSniff = new BaseCamelCapsFunctionNameSniff();
        $this->strict = &$this->decoratedSniff->strict;
    }


    /**
     * @return array<int|string>
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
        if (SuppressHelper::isSniffSuppressed($phpcsFile, $pointer, self::NAME)) {
            return;
        }

        $this->decoratedSniff->process($phpcsFile, $pointer);
    }
}
