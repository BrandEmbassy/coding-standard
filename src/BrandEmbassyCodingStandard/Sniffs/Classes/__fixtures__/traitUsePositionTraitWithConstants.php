<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes\__fixtures__;

use Bar;
use FooTrait;

final class FooBarClass
{
    private const FOO = 'foo';
    private const BAR = 'bar';

    use Bar;
    use FooTrait;

    public function foo(): string
    {
        return self::FOO;
    }
}
