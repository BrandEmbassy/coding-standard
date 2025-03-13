<?php

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__;

final class SkipSameClassReference extends \PHPUnit\Framework\TestCase
{
    private const BAR = 1;

    public function baz(): void
    {
        echo SkipSameClassReference::BAR;
    }
}
