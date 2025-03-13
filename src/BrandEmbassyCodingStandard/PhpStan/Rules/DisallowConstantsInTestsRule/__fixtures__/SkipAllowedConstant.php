<?php

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__;

final class SkipAllowedConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo AllowedConstant::FOO;
    }
}

final class AllowedConstant {
    public const FOO = 1;
}
