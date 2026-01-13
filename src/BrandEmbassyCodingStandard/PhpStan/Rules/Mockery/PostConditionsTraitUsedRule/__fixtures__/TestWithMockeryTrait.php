<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\PostConditionsTraitUsedRule\__fixtures__;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class TestWithMockeryTrait extends TestCase
{
    use MockeryPHPUnitIntegration;


    public function testSomething(): void
    {
        $mock = Mockery::mock('SomeClass');

        $mock->expects('doSomething');

        $mock->shouldReceive('doSomething')
            ->once();

        $mock->shouldReceive('doSomething')
            ->twice();

        $mock->shouldReceive('doSomething')
            ->times(2);

        $mock->shouldReceive('doSomething')
            ->zeroOrMoreTimes();

        $mock->shouldReceive('doSomething')
            ->atLeast()
            ->once();

        $mock->shouldReceive('doSomething')
            ->atMost()
            ->once();

        $mock->shouldReceive('doSomething')
            ->never();

        $mock->shouldHaveReceived('doSomething');
    }
}
