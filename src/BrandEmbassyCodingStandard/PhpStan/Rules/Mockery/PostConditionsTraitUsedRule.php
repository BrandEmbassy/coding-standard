<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use function assert;
use function in_array;
use function sprintf;

class PostConditionsTraitUsedRule implements Rule
{
    /**
     * @var string[]
     */
    private static $callCountMethods = [
        'zero',
        'once',
        'twice',
        'times',
        'zeroOrMoreTimes',
        'atLeast',
        'never',
        'atMost',
        'between',
    ];

    /**
     * @var RuleLevelHelper
     */
    private $ruleLevelHelper;


    public function __construct(RuleLevelHelper $ruleLevelHelper)
    {
        $this->ruleLevelHelper = $ruleLevelHelper;
    }


    public function getNodeType(): string
    {
        return MethodCall::class;
    }


    /**
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof MethodCall);

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
            static function (Type $type) use ($name): bool {
                return $type->canCallMethods()->yes() && $type->hasMethod($name)->yes();
            }
        );

        $type = $typeResult->getType();

        if (!$type instanceof ObjectType || $type->getClassName() !== 'Mockery\\Expectation') {
            return [];
        }

        $classReflection = $scope->getClassReflection();

        if ($classReflection === null) {
            return [];
        }

        $traitName = 'Mockery\\Adapter\\Phpunit\\MockeryPHPUnitIntegration';
        if (!$classReflection->hasTraitUse($traitName)) {
            return [sprintf('Calling %s without %s trait.', $name, $traitName)];
        }

        return [];
    }
}
