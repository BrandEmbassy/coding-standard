<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\NetteStringsContainsToNativeCallRector;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see NetteStringsContainsToNativeCallRectorTest
 * @final
 */
class NetteStringsContainsToNativeCallRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace calls to Strings::contains with str_contains.',
            [
                new CodeSample(
                    // Code before
                    '$result = Strings::contains($haystack, $needle);',
                    // Code after
                    '$result = str_contains($haystack, $needle);',
                ),
            ],
        );
    }


    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }


    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof StaticCall) {
            return null;
        }

        if (!$this->isObjectType($node->class, new ObjectType('Nette\\Utils\\Strings'))) {
            return null;
        }

        if (!$this->isName($node->name, 'contains')) {
            return null;
        }

        return $this->nodeFactory->createFuncCall('str_contains', $node->args);
    }
}
