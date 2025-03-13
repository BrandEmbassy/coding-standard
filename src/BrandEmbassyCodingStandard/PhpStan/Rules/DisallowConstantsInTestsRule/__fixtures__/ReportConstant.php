<?php

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__;

final class ReportConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo SomeDisallowedConstant::FOO;
    }
}

final class SomeDisallowedConstant {
    public const FOO = '1';
}
