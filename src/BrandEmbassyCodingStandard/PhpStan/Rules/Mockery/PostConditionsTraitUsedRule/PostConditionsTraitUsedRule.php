<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery\PostConditionsTraitUsedRule;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Type\Type;
use PHPUnit\Framework\TestCase;
use function in_array;
use function sprintf;

/**
 * @implements Rule<MethodCall>
 */
class PostConditionsTraitUsedRule implements Rule
{
    /**
     * @var string[]
     */
    private static array $callCountMethods = [
        'zero',
        'once',
        'twice',
        'times',
        'zeroOrMoreTimes',
        'atLeast',
        'never',
        'atMost',
        'between',
        'expects',
        'shouldHaveReceived',
    ];

    private RuleLevelHelper $ruleLevelHelper;


    public function __construct(RuleLevelHelper $ruleLevelHelper)
    {
        $this->ruleLevelHelper = $ruleLevelHelper;
    }


    public function getNodeType(): string
    {
        return MethodCall::class;
    }


    /**
     * @param MethodCall $node
     *
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        if (!$node->name instanceof Identifier) {
            return [];
        }

        $name = $node->name->name;

        if (!in_array($name, self::$callCountMethods, true)) {
            return [];
        }

        $typeResult = $this->ruleLevelHelper->findTypeToCheck(
            $scope,
            $node->var,
            sprintf('Call to method %s() on an unknown class %%s.', $name),
            static fn(Type $type): bool => $type->canCallMethods()
                ->yes() && $type->hasMethod($name)
                ->yes(),
        );

        $type = $typeResult->getType();

        if (in_array('Mockery\\Expectation', $type->getObjectClassNames(), true)) {
            return [];
        }

        $classReflection = $scope->getClassReflection();

        if ($classReflection === null) {
            return [];
        }

        if (!$classReflection->isSubclassOf(TestCase::class)) {
            return [];
        }

        $traitName = 'Mockery\\Adapter\\Phpunit\\MockeryPHPUnitIntegration';
        if (!$classReflection->hasTraitUse($traitName)) {
            $errors[] = RuleErrorBuilder::message(sprintf('Calling %s without %s trait.', $name, $traitName))
                ->identifier('postConditions.noMockeryTrait')
                ->nonIgnorable()
                ->build();
        }

        return $errors;
    }
}
