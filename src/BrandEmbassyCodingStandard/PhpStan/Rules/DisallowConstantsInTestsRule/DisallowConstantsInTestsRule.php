<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\DisallowConstantsInTestsRule;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPUnit\Framework\TestCase;
use Rector\Skipper\Matcher\FileInfoMatcher;
use function in_array;
use function sprintf;

/**
 * Using production constants in tests can create unwanted behaviour or bugs.
 * When the constant value changes, the test will not be aware of it and will continue to pass.
 * For more info see https://dev.to/scottshipp/don-t-use-non-test-constants-in-unit-tests-3ej0
 *
 * The rule has configuration for allowed constants and patterns, for example to allow test constants to be used freely, or to allow constants from vendor packages.
 *
 * @implements Rule<ClassConstFetch>
 */
class DisallowConstantsInTestsRule implements Rule
{
    /**
     * @param array<string> $allowedPatterns
     * @param array<class-string> $allowedConstants
     */
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly FileInfoMatcher $fileInfoMatcher,
        private readonly array $allowedPatterns = [],
        private readonly array $allowedConstants = [],
    ) {
    }


    public function getNodeType(): string
    {
        return ClassConstFetch::class;
    }


    /**
     * @param ClassConstFetch $node
     *
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->class instanceof Name) {
            return [];
        }

        if (!$node->name instanceof Identifier) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return [];
        }

        $nodeClassReflection = $this->reflectionProvider->getClass($classReflection->getName());

        $className = (string)$node->class;

        if (!$nodeClassReflection->isSubclassOf(TestCase::class)) {
            return [];
        }

        if ($className === 'self') {
            return [];
        }

        $constantName = (string)$node->name;

        if ($constantName === 'class') {
            return [];
        }

        $constantClassReflection = $this->reflectionProvider->getClass($className);

        if (in_array($constantClassReflection->getName(), $this->allowedConstants, true)) {
            return [];
        }

        if ($this->fileInfoMatcher->doesFileInfoMatchPatterns(
            $constantClassReflection->getFileName() ?? '',
            $this->allowedPatterns,
        )) {
            return [];
        }

        if (!$constantClassReflection->hasConstant($constantName)) {
            return [];
        }

        if ($constantClassReflection->isEnum()) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf('Usage of production constant %s::%s in tests is disallowed.', $node->class, $node->name),
            )
                ->identifier('constants.sourceCodeConstantUsage')
                ->tip(
                    'Use a hard-coded value instead. That way, if the constant value changes, we will be warned by the test and we can prevent unwanted behaviour.',
                )
                ->build(),
        ];
    }
}
