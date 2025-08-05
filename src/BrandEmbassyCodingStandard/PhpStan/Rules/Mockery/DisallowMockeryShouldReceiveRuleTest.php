<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<DisallowMockeryShouldReceiveRule>
 */
class DisallowMockeryShouldReceiveRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new DisallowMockeryShouldReceiveRule();
    }


    public function testRuleWithShouldReceive(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/TestCaseWithShouldReceiveExpectation.php'], [
            [
                'Calling Mockery\MockInterface::shouldReceive() is forbidden.',
                13,
                'Use Mockery\MockInterface::expects() instead, which will also assert call count.',
            ],
        ]);
    }


    public function testRuleWithExpects(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/TestCaseWithExpectsExpectation.php'], []);
    }
}
