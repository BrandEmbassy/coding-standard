<?php declare(strict_types = 1);

namespace Foo\Bar;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class FooFooFoo extends TestCase
{
    private static $lol;

    public function barBarBar(): void
    {
        self::$lol = 'A';
        Assert::assertTrue(true);
        self::assertFalse(false);
        static::assertEquals(1, 2);
        self::$lol = 'B';
        static  ::
        assertTrue(false);
        self::lolSelf();
        static::lolStatic();
        self::any();
        self::$lol = 'C';
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
