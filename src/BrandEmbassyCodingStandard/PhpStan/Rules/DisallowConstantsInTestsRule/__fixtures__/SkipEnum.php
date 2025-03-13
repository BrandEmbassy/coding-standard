<?php

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__;

final class SkipEnum extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo SomeEnum::FOO->value;
    }
}

enum SomeEnum: int {
    case FOO = 1;
}
