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

    /**
     * @var BaseCamelCapsFunctionNameSniff
     */
    private $decoratedSniff;

    /**
     * @var bool
     */
    public $strict;


    public function __construct()
    {
        $this->decoratedSniff = new BaseCamelCapsFunctionNameSniff();
        $this->strict = &$this->decoratedSniff->strict;
    }


    /**
     * @return int[]
     */
    public function register(): array
    {
        return $this->decoratedSniff->register();
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
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
