<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use ReflectionClass;
use function assert;
use function str_contains;
use function strtoupper;

/**
 * @final
 */
class MabeEnumFactory
{
    private const MABE_ENUM_CLASS_NAME = 'MabeEnum\\Enum';

    private NodeFactory $nodeFactory;

    private NodeTypeResolver $nodeTypeResolver;

    private NodeNameResolver $nodeNameResolver;

    private ClassNameProvider $classNameProvider;

    private bool $ignoredClassFromVendor = true;


    public function __construct(
        NodeFactory $nodeFactory,
        NodeTypeResolver $nodeTypeResolver,
        NodeNameResolver $nodeNameResolver,
        ClassNameProvider $classNameProvider,
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->classNameProvider = $classNameProvider;
    }


    public function createFromNode(StaticCall|MethodCall $node, bool $ignoreClassesFromVendor = true): ?Expr
    {
        $this->ignoredClassFromVendor = $ignoreClassesFromVendor;

        if ($node->name instanceof Expr) {
            return null;
        }

        $enumCaseName = $this->nodeNameResolver->getName($node->name);
        if ($enumCaseName === null) {
            return null;
        }

        if ($node instanceof StaticCall) {
            return $this->refactorStaticCall($node, $enumCaseName);
        }

        return $this->refactorMethodCall($node, $enumCaseName);
    }


    private function createPropertyValueFetch(Expr $expr): PropertyFetch
    {
        return $this->nodeFactory->createPropertyFetch($expr, 'value');
    }


    private function refactorStaticCall(StaticCall $staticCall, string $staticCallName): ?Expr
    {
        $class = $staticCall->class;
        if (!$class instanceof Name) {
            return null;
        }

        $className = $this->classNameProvider->getClassNameFromClass($class);

        if ($className === null) {
            return null;
        }

        if ($this->isClassIgnored($className)) {
            return null;
        }

        if (strtoupper($staticCallName) === $staticCallName) {
            return $this->nodeFactory->createClassConstFetch($className, $staticCallName);
        }

        if ($staticCallName === 'get') {
            return $this->refactorGetStaticCall($staticCall);
        }

        if ($staticCallName === 'getEnumerators') {
            return $this->nodeFactory->createStaticCall($className, 'cases');
        }

        if ($staticCallName === 'has') {
            return $this->refactorHasStaticCall($staticCall);
        }

        return null;
    }


    private function refactorMethodCall(MethodCall $methodCall, string $methodName): ?Expr
    {
        if (!$this->isMabeEnum($methodCall->var)) {
            return null;
        }

        $className = $this->classNameProvider->getClassNameFromNode($methodCall->var);

        if ($className === null) {
            return null;
        }

        if ($this->isClassIgnored($className)) {
            return null;
        }

        if ($methodName === 'is') {
            return $this->refactorIsMethodCall($methodCall);
        }

        if ($methodName === 'getValue') {
            return $this->createPropertyValueFetch($methodCall->var);
        }

        if ($methodName === 'getName') {
            return $this->nodeFactory->createPropertyFetch($methodCall->var, 'name');
        }

        return null;
    }


    private function refactorHasStaticCall(StaticCall $staticCall): BinaryOp
    {
        $class = $staticCall->class;
        assert($class instanceof Name);

        $left = $this->nodeFactory->createStaticCall($class->toString(), 'tryFrom', $staticCall->getArgs());

        return new NotIdentical($left, $this->nodeFactory->createNull());
    }


    private function refactorGetStaticCall(StaticCall $staticCall): ?Expr
    {
        $value = $staticCall->getArgs()[0]->value;

        if ($value instanceof ClassConstFetch) {
            $name = $value->name;

            if (!$name instanceof Identifier) {
                return null;
            }

            $className = $this->nodeNameResolver->getName($value->class);

            if ($className === null) {
                return null;
            }

            return $this->nodeFactory->createClassConstFetch(
                $className,
                $name->name,
            );
        }

        if ($value instanceof String_ || $value instanceof Variable) {
            $className = $this->nodeNameResolver->getName($staticCall->class);

            if ($className === null) {
                return null;
            }

            return $this->nodeFactory->createStaticCall($className, 'from', [$value]);
        }

        return null;
    }


    private function refactorIsMethodCall(MethodCall $methodCall): ?Identical
    {
        $expr = $methodCall->var;

        if ($expr instanceof StaticCall) {
            $expr = $expr->getArgs()[0]->value;
        }

        $arg = $methodCall->getArgs()[0] ?? null;
        if (!$arg instanceof Arg) {
            return null;
        }

        $right = $arg->value;
        if ($right instanceof StaticCall) {
            $right = $right->getArgs()[0]->value;
        }

        if ($arg->value instanceof ClassConstFetch) {
            $right = $arg->value;
        }

        return new Identical($expr, $right);
    }


    /**
     * @param class-string $className
     */
    private function isClassIgnored(string $className): bool
    {
        return $this->ignoredClassFromVendor && $this->isClassFromVendor($className);
    }


    private function isMabeEnum(Node $node): bool
    {
        return $this->nodeTypeResolver->isObjectType(
            $node,
            new ObjectType(self::MABE_ENUM_CLASS_NAME),
        );
    }


    /**
     * @param class-string $className
     */
    private function isClassFromVendor(string $className): bool
    {
        $classReflection = new ReflectionClass($className);

        return $classReflection->getFileName() !== false && str_contains($classReflection->getFileName(), 'vendor');
    }
}
