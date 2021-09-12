<?php declare(strict_types = 1);

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class FooTest extends TestCase
{
    public function testFoo(): void
    {
        Assert::assertTrue(true);
        self::assertFalse(false);
    }
}
