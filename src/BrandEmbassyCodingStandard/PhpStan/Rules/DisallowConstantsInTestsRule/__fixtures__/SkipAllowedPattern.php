<?php

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__;

final class SkipAllowedPattern extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo SomeConstantWithAllowedConstantPattern::FOO;
    }
}

final class SomeConstantWithAllowedConstantPattern {
    public const FOO = 1;
}
