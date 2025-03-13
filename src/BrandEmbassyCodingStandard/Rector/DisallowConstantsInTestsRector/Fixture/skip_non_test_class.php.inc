<?php

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\Fixture;

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
