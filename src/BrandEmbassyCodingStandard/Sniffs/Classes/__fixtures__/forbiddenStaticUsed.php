<?php declare(strict_types = 1);

namespace Foo\Bar;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class FooFooFoo extends TestCase
{
    public function barBarBar(): void
    {
        Assert::assertTrue(true);
        self::assertFalse(false);
        static::assertEquals(1, 2);
        static  ::
        assertEquals(1, 2);
        self::any();
    }


    private static function lolStatic(): void
    {
        new static();
    }


    private
    static
    function lolSelf(): void
    {
        new
        self();
    }
}
