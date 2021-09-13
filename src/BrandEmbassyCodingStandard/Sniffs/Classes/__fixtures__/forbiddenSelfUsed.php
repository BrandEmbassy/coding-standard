<?php declare(strict_types = 1);

namespace Foo\Bar;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class FooTest extends TestCase
{
    public function testFoo(): void
    {
        Assert::assertTrue(true);
        self::assertFalse(false);
        static::assertEquals(1, 2);
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
