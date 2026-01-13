<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\PostConditionsTraitUsedRule;

use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<PostConditionsTraitUsedRule>
 */
class PostConditionsTraitUsedRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PostConditionsTraitUsedRule(
            new RuleLevelHelper( // @phpstan-ignore phpstanApi.constructor (we will just update it when we update phpstan, it's minor impact)
                $this->createReflectionProvider(),
                true,
                true,
                true,
                true,
                true,
                true,
                true,
            ),
        );
    }


    public function testTestWithoutMockeryTrait(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestWithoutMockeryTrait.php'],
            [
                [
                    'Calling expects without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    14,
                ],
                [
                    'Calling once without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    16,
                ],
                [
                    'Calling twice without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    19,
                ],
                [
                    'Calling times without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    22,
                ],
                [
                    'Calling zeroOrMoreTimes without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    25,
                ],
                [
                    'Calling once without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    28,
                ],
                [
                    'Calling atLeast without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    28,
                ],
                [
                    'Calling once without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    32,
                ],
                [
                    'Calling atMost without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    32,
                ],
                [
                    'Calling never without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    36,
                ],
                [
                    'Calling shouldHaveReceived without Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration trait.',
                    39,
                ],
            ],
        );
    }


    public function testTestWithMockeryTrait(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestWithMockeryTrait.php'],
            [],
        );
    }


    public function testNonTestClassWithoutMockeryTrait(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/NonTestClassWithoutMockeryTrait.php'],
            [],
        );
    }
}
