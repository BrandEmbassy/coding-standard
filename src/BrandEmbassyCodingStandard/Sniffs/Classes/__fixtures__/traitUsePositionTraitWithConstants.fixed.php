<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes\__fixtures__;

use Bar;
use FooTrait;

final class FooBarClass
{

    use Bar;
    use FooTrait;
    private const FOO = 'foo';
    private const BAR = 'bar';

    public function foo(): string
    {
        return self::FOO;
    }
}
