<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\__fixtures__;

use Mockery;
use PHPUnit\Framework\TestCase;

class TestCaseWithShouldReceiveExpectation extends TestCase
{
    public function testSomething(): void
    {
        $mock = Mockery::mock(SomeClass::class);
        $mock->shouldReceive('returnOne')
            ->andReturn(1);
    }
}
