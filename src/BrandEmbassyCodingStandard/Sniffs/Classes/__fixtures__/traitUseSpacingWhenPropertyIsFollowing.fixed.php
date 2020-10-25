<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes\__fixtures__;

use Bar;
use Trait_Test;

final class FooBarClass4
{
    use Bar;
    use Trait_Test;

    private $foo = 'foo';
}
