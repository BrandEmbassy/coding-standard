<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\NamingConvention\__fixtures__;

/**
 * @description of dummy test case
 *
 * dUmMy should be also reported
 */
final class DummyTestClass
{
    private const DUMMY_VALUE = 'something';
    private const ANOTHER_CONST = 'dummy';

    /**
     * @var string
     */
    private $dummyProperty;


    public function __construct(string $value = 'dummy')
    {
        $this->dummyProperty = $value ?? self::ANOTHER_CONST;
    }


    public function createDummy(): self
    {
        return new self(self::DUMMY_VALUE);
    }


    /**
     * This is annotation for default value where dummy could be mentioned too
     *
     * @param string $dummyValue
     */
    public function createWithArgument(string $dummyValue): self
    {
        return new self($dummyValue);
    }


    public function createWithReallyLongDefaultValueToCheckIfItGetsCorrectLine(
        string $defaultValueShouldBeOnNextLine = 'thisIsDummyValue'
    ): self {
        return new self($defaultValueShouldBeOnNextLine ?? 'thereCouldBeAnotherDummyValue');
    }
}
