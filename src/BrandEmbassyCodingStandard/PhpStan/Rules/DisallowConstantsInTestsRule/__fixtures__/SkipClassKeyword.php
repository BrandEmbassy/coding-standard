<?php

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__;

final class SkipClassKeyword
{
    public function baz(): void
    {
        echo SkipClass::class;
    }
}
