<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\TestsExtendMockeryTestCaseRule;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\TestCase;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use SlevomatCodingStandard\Sniffs\TestCase as SlevomatCodingStandardTestCase;
use function sprintf;

/**
 * @implements Rule<Class_>
 */
class TestsExtendMockeryTestCaseRule implements Rule
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }


    public function getNodeType(): string
    {
        return Class_::class;
    }


    /**
     * @param Class_ $node
     *
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->name === null) {
            return [];
        }

        $namespacedName = $node->namespacedName;

        if ($namespacedName === null) {
            return [];
        }

        $className = $scope->resolveName($namespacedName);

        if (!$this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $class = $this->reflectionProvider->getClass($className);

        if (!$class->isSubclassOf(TestCase::class)) {
            return [];
        }

        // Allow these code quality tools test cases as they are not using Mockery
        if ($class->isSubclassOf(SlevomatCodingStandardTestCase::class)
            || $class->isSubclassOf(AbstractRectorTestCase::class)
            || $class->isSubclassOf(RuleTestCase::class)) {
            return [];
        }

        if ($class->isSubclassOf(MockeryTestCase::class)) {
            return [];
        }

        $ruleErrorMessage = sprintf(
            'PHPUnit test %s must extend %s (directly or indirectly).',
            $class->getName(),
            MockeryTestCase::class,
        );

        return [
            RuleErrorBuilder::message($ruleErrorMessage)
                ->identifier('mockery.testCase')
                ->nonIgnorable()
                ->build(),
        ];
    }
}
