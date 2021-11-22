<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class PhpUnitTest extends TestCase
{
    public function testFunctionPublic(): void
    {
        Assert::assertTrue(true);
    }


    protected function testFunctionProtected(): void
    {
        Assert::assertTrue(true);
    }


    private function testFunctionPrivate(): void
    {
        Assert::assertTrue(true);
    }


    public function nonTestFunction(): void
    {
        Assert::assertTrue(true);
    }
}
