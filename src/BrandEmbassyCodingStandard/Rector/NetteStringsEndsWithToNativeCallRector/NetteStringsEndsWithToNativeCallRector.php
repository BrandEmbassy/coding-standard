<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\NetteStringsEndsWithToNativeCallRector;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see NetteStringsEndsWithToNativeCallRectorTest
 * @final
 */
class NetteStringsEndsWithToNativeCallRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace calls to Strings::endsWith with str_ends_with.',
            [
                new CodeSample(
                    // Code before
                    '$result = Strings::endsWith($haystack, $needle);',
                    // Code after
                    '$result = str_ends_with($haystack, $needle);',
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

        if (!$this->isName($node->name, 'endsWith')) {
            return null;
        }

        return $this->nodeFactory->createFuncCall('str_ends_with', $node->args);
    }


    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::STR_ENDS_WITH;
    }
}
