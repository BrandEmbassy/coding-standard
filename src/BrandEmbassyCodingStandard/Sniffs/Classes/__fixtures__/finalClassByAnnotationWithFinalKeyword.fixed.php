<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

class Foo
{
    private int $x;


    final public function __construct()
    {
        $this->x = 5;
    }


    final public function getX(): int
    {
        return $this->x;
    }
}

/**
 * @final
 */
class Bar extends Foo
{
    public function doSomething(): bool
    {
        return true;
    }
}

/**
 * @ORM\Entity
 *
 * @final
 */
class Baz
{
    public function doSomething(): bool
    {
        return true;
    }
}
