<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem as ArrayItemNode;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_map;
use function assert;
use function class_exists;
use function is_numeric;
use function is_string;
use function preg_match;
use function strtoupper;

/**
 * @final
 *
 * Not all methods are implemented because they have no equivalent in the native PHP enum.
 * SomeEnum::getValues() is not implemented because it is not possible to get all enum values in PHP.
 * array_column(SomeEnum::cases(), 'value') can be used instead.
 *
 * If we use only constants from enum (eg: $var = $bool? SomeEnum::USER: SomeEnum::SUPERUSER), they become objects in case of native enums, and it is necessary to add the call omeEnum::USER->value: SomeEnum::SUPERUSER->value or $var->value.
 * If the Enum is created from a variable, and this variable is a value of the const from enum, it is necessary to manually modify the created enum.
 * Eg: $var = SomeEnum::USER; $enum = SomeEnum::get($var); -> $enum = SomeEnum::USER;
 */
class MabeEnumMethodCallToEnumConstRector extends AbstractRector implements MinPhpVersionInterface, ConfigurableRectorInterface
{
    public const IGNORED_CLASSES_REGEX = 'ignored_classes_regex';

    private const MABE_ENUM_CLASS_NAME = 'MabeEnum\\Enum';

    /**
     * @var array<string, mixed>
     */
    private array $configuration = [];


    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor Mabe enum fetch to Enum const', [
            new CodeSample(
                '$enum = SomeEnum::get(SomeEnum::VALUE);',
                '$enum = SomeEnum::VALUE;',
            ),
            new CodeSample(
                '$enum->getValue()',
                '$enum->value',
            ),
            new CodeSample(
                '$enum->getName()',
                '$enum->name',
            ),
            new CodeSample(
                '$enum->is(SomeEnum::VALUE)',
                '$enum === SomeEnum::VALUE',
            ),
            // If the enum is from another package, it can be ignored, ig. vendor/BrandEmbassy/...
            new ConfiguredCodeSample(
                'IgnoredEnum::getValue()',
                'IgnoredEnum::getValue()',
                [
                    self::IGNORED_CLASSES_REGEX => '/.*IgnoredEnum/',
                ],
            ),
        ]);
    }


    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            MethodCall::class,
            StaticCall::class,
            Array_::class,
        ];
    }


    public function refactor(Node $node)
    {
        // Skip if MabeEnum is not part of the project
        if (!class_exists(self::MABE_ENUM_CLASS_NAME)) {
            return null;
        }

        if ($node instanceof Array_) {
            return $this->refactorArray($node);
        }

        assert($node instanceof MethodCall || $node instanceof StaticCall);

        if ($node->name instanceof Expr) {
            return null;
        }

        $enumCaseName = $this->getName($node->name);
        if ($enumCaseName === null) {
            return null;
        }

        if ($node instanceof StaticCall) {
            return $this->refactorStaticCall($node, $enumCaseName);
        }

        return $this->refactorMethodCall($node, $enumCaseName);
    }


    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }


    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        if ($configuration === []) {
            $configuration = [self::IGNORED_CLASSES_REGEX => null];
        }

        $this->configuration = $configuration;
    }


    private function refactorArray(Array_ $array): ?Expr
    {
        if ($array->items === []) {
            return null;
        }

        /** @var ArrayItem[] $arrayItems */
        $arrayItems = $array->items;

        /** @var ArrayItemNode[] $refactoredArrayItems */
        $refactoredArrayItems = array_map(fn(ArrayItem $arrayItem): ArrayItem => $this->refactorArrayItemKeys($arrayItem), $arrayItems);

        $array->items = $refactoredArrayItems;

        return $array;
    }


    private function refactorArrayItemKeys(ArrayItem $arrayItem): ArrayItem
    {
        $key = $arrayItem->key;

        if ($key === null) {
            return $arrayItem;
        }

        if ($key instanceof ClassConstFetch && $this->isMabeEnum($key->class)) {
            $key = $this->nodeFactory->createPropertyFetch($key, 'value');
            $arrayItem->key = $key;
        }

        return $arrayItem;
    }


    private function refactorStaticCall(StaticCall $staticCall, string $staticCallName): ?Expr
    {
        $className = $this->getName($staticCall->class);
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

        return null;
    }


    private function refactorMethodCall(MethodCall $methodCall, string $methodName): ?Expr
    {
        if (!$this->isMabeEnum($methodCall->var)) {
            return null;
        }

        $className = $this->getClassName($methodCall->var);

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
            return $this->nodeFactory->createPropertyFetch($methodCall->var, 'value');
        }

        if ($methodName === 'getName') {
            return $this->nodeFactory->createPropertyFetch($methodCall->var, 'name');
        }

        return null;
    }


    private function refactorGetStaticCall(StaticCall $staticCall): ?Expr
    {
        $value = $staticCall->getArgs()[0]->value;

        if ($value instanceof ClassConstFetch) {
            $name = $value->name;

            if (!$name instanceof Identifier) {
                return null;
            }

            $className = $this->getName($value->class);

            if ($className === null) {
                return null;
            }

            return $this->nodeFactory->createClassConstFetch(
                $className,
                $name->name,
            );
        }

        if ($value instanceof String_ || $value instanceof Variable) {
            $className = $this->getName($staticCall->class);

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


    private function isMabeEnum(Node $node): bool
    {
        return $this->isObjectType($node, new ObjectType(self::MABE_ENUM_CLASS_NAME));
    }


    private function getIgnoredClassesRegex(): ?string
    {
        $ignoredClassesRegex = $this->configuration[self::IGNORED_CLASSES_REGEX];
        assert(is_string($ignoredClassesRegex) || $ignoredClassesRegex === null);

        return $ignoredClassesRegex;
    }


    private function isClassIgnored(string $className): bool
    {
        $ignoredClassesRegex = $this->getIgnoredClassesRegex();
        $match = $ignoredClassesRegex !== null ? preg_match($ignoredClassesRegex, $className) : false;

        return is_numeric($match) && $match > 0;
    }


    private function getClassName(Node $node): ?string
    {
        $type = $this->getType($node);

        if (!$type instanceof TypeWithClassName) {
            return null;
        }

        return $type->getClassName();
    }
}
