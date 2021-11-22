<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\TestCase;
use function assert;
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

    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;


    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }


    public function getNodeType(): string
    {
        return ClassMethod::class;
    }


    /**
     * @param ClassMethod $node
     *
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $methodName = $node->name->name;

        if (preg_match('~^test[A-Z0-9]~', $methodName) === 0) {
            return [];
        }

        $classNode = $node->getAttribute('parent');
        assert($classNode instanceof Class_);

        $className = (string)$classNode->namespacedName;
        if (!$this->reflectionProvider->hasClass($className)) {
            return [sprintf('Vampire class error: cannot get reflection of class %s.', $className)];
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        $violations = [];
        if (!$classReflection->isSubclassOf(TestCase::class)) {
            $violationReason = sprintf('the class is not a %s', TestCase::class);
            $violations[] = $this->getTestMethodViolation($className, $methodName, $violationReason);
        }
        if (substr($className, -strlen(self::TEST_CLASS_SUFFIX)) !== self::TEST_CLASS_SUFFIX) {
            $violationReason = sprintf('the class is not suffixed %s', self::TEST_CLASS_SUFFIX);
            $violations[] = $this->getTestMethodViolation($className, $methodName, $violationReason);
        }
        if (!$node->isPublic()) {
            $violations[] = $this->getTestMethodViolation($className, $methodName, 'it is not public');
        }

        return $violations;
    }


    protected function getTestMethodViolation(string $className, string $methodName, string $reason): string
    {
        return sprintf('Method %s::%s() seems like a test method, but %s.', $className, $methodName, $reason);
    }
}
