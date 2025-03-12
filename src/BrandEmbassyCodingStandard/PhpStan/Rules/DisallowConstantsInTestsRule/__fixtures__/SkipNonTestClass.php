<?php

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__;

final class SkipNonTestClass
{
    public function baz(): void
    {
        echo ProductionConstant::FOO;
    }
}

final class ProductionConstant {
    public const FOO = 1;
}
