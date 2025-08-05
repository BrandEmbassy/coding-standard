<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Mockery;

use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements Rule<MethodCall>
 */
class DisallowMockeryShouldReceiveRule implements Rule
{
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
        if (!$node->name instanceof Identifier || $node->name->toString() !== 'shouldReceive') {
            return [];
        }

        $calledOnType = $scope->getType($node->var);
        $targetType = new ObjectType(LegacyMockInterface::class);
        if (!$targetType->isSuperTypeOf($calledOnType)->yes()) {
            return [];
        }

        return [
            RuleErrorBuilder::message('Calling ' . MockInterface::class . '::shouldReceive() is forbidden.')
                ->identifier('mockery.shouldReceive')
                ->tip('Use ' . MockInterface::class . '::expects() instead, which will also assert call count.')
                ->build(),
        ];
    }
}
