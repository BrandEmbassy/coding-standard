<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\__fixtures__;

use Mockery;
use PHPUnit\Framework\TestCase;

class TestCaseWithExpectsExpectation extends TestCase
{
    public function testSomething(): void
    {
        $mock = Mockery::mock(SomeClass::class);
        $mock->expects('returnOne')
            ->andReturn(1);
    }
}
