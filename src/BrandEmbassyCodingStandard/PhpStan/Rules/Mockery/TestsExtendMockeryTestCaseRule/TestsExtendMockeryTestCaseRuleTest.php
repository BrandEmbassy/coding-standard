<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\TestsExtendMockeryTestCaseRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<TestsExtendMockeryTestCaseRule>
 */
class TestsExtendMockeryTestCaseRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TestsExtendMockeryTestCaseRule($this->createReflectionProvider());
    }


    public function testExtendsMockeryTestCase(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestExtendsMockeryTestCase.php'],
            [],
        );
    }


    public function testExtendsClassWhichExtendsMockeryTestCase(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestExtendsClassWhichExtendsMockeryTestCase.php'],
            [],
        );
    }


    public function testExtendsPhpUnitTestCase(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestExtendsPhpUnitTestCase.php'],
            [
                [
                    'PHPUnit test BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\TestsExtendMockeryTestCaseRule\__fixtures__\TestExtendsPhpUnitTestCase must extend Mockery\Adapter\Phpunit\MockeryTestCase (directly or indirectly).',
                    7,
                ],
            ],
        );
    }


    public function testExtendsClassWhichExtendsPhpUnitTestCase(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestExtendsClassWhichExtendsPhpUnitTestCase.php'],
            [
                [
                    'PHPUnit test BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\TestsExtendMockeryTestCaseRule\__fixtures__\TestExtendsClassWhichExtendsPhpUnitTestCase must extend Mockery\Adapter\Phpunit\MockeryTestCase (directly or indirectly).',
                    7,
                ],
            ],
        );
    }


    public function testExtendsPhpstanRuleTestCase(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestExtendsPhpstanRuleTestCase.php'],
            [],
        );
    }


    public function testExtendsRectorRuleTestCase(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestExtendsRectorRuleTestCase.php'],
            [],
        );
    }


    public function testExtendsSlevomatCodingStandardTestCase(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/TestExtendsSlevomatCodingStandardTestCase.php'],
            [],
        );
    }
}
