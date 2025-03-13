<?php

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\Fixture;

final class SkipAllowedPattern extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo SomeEnum::FOO->value;
    }
}

enum SomeEnum: int {
    case FOO = 1;
}
