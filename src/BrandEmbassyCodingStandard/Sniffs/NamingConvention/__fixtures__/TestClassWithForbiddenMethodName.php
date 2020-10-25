<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\NamingConvention\__fixtures__;

final class TestClassWithForbiddenMethodName
{
    public static function createDummy(): self
    {
        return new self();
    }
}
