<?php

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\Fixture;

final class SkipClass
{
    public function baz(): void
    {
        echo SkipClass::class;
    }
}
