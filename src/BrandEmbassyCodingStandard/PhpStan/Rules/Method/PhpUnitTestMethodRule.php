<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\TestCase;
use function preg_match;
use function sprintf;
use function strlen;
use function substr;

/**
 * @implements Rule<ClassMethod>
 */
class PhpUnitTestMethodRule implements Rule
{
    private const TEST_CLASS_SUFFIX = 'Test';


    public function getNodeType(): string
    {
        return ClassMethod::class;
    }


    /**
     * @param ClassMethod $node
     *
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $methodName = $node->name->name;

        if (preg_match('~^test[A-Z0-9]~', $methodName) === 0) {
            return [];
        }

        if (!$scope->isInClass()) {
            return [];
        }

        /** @var ClassReflection $classReflection */
        $classReflection = $scope->getClassReflection();
        $className = $classReflection->getName();

        $violations = [];
        if (!$classReflection->isSubclassOf(TestCase::class)) {
            $violationReason = sprintf('the class is not a %s', TestCase::class);
            $violations[] = $this->getTestMethodViolation($className, $methodName, $violationReason, 'phpUnitTestMethod.notTestCase');
        }

        if (substr($className, -strlen(self::TEST_CLASS_SUFFIX)) !== self::TEST_CLASS_SUFFIX) {
            $violationReason = sprintf('the class is not suffixed %s', self::TEST_CLASS_SUFFIX);
            $violations[] = $this->getTestMethodViolation($className, $methodName, $violationReason, 'phpUnitTestMethod.missingSuffix');
        }

        if (!$node->isPublic()) {
            $violations[] = $this->getTestMethodViolation($className, $methodName, 'it is not public', 'phpUnitTestMethod.notPublic');
        }

        return $violations;
    }


    protected function getTestMethodViolation(string $className, string $methodName, string $reason, string $identifier): IdentifierRuleError
    {
        return RuleErrorBuilder::message(sprintf('Method %s::%s() seems like a test method, but %s.', $className, $methodName, $reason))
            ->identifier($identifier)
            ->build();
    }
}
