<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes\__fixtures__;

use Bar;

final class FooBarClass3
{
    use Bar;


    public function foo(): string
    {
        return 'foo';
    }
}
