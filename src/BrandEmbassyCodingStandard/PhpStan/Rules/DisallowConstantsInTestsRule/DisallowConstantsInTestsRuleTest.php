<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule;

use BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__\AllowedConstant;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\Skipper\FileSystem\FnMatchPathNormalizer;
use Rector\Skipper\Fnmatcher;
use Rector\Skipper\Matcher\FileInfoMatcher;
use Rector\Skipper\RealpathMatcher;

/**
 * @extends RuleTestCase<DisallowConstantsInTestsRule>
 */
class DisallowConstantsInTestsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new DisallowConstantsInTestsRule(
            $this->createReflectionProvider(),
            new FileInfoMatcher(new FnMatchPathNormalizer(), new Fnmatcher(), new RealpathMatcher()),
            ['*AllowedPattern.php'],
            [AllowedConstant::class],
        );
    }


    public function testRuleSkipsAllowedConstant(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/SkipAllowedConstant.php'], []);
    }


    public function testRuleSkipsAllowedPattern(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/SkipAllowedPattern.php'], []);
    }


    public function testRuleSkipsClassKeyword(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/SkipClassKeyword.php'], []);
    }


    public function testRuleSkipsEnum(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/SkipEnum.php'], []);
    }


    public function testRuleSkipsNonTestClass(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/SkipNonTestClass.php'], []);
    }


    public function testRuleSkipsSelfKeyword(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/SkipSelfKeyword.php'], []);
    }


    public function testRuleReportsConstant(): void
    {
        $this->analyse([__DIR__ . '/__fixtures__/ReportConstant.php'], [
            [
                'Usage of production constant BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule\__fixtures__\SomeDisallowedConstant::FOO in tests is disallowed.',
                9,
                'Use a hard-coded value instead. That way, if the constant value changes, we will be warned by the test and we can prevent unwanted behaviour.',
            ],
        ]);
    }
}
