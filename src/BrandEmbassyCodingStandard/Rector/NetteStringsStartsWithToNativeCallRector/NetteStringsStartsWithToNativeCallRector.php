<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\NetteStringsStartsWithToNativeCallRector;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see NetteStringsStartsWithToNativeCallRectorTest
 * @final
 */
class NetteStringsStartsWithToNativeCallRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace calls to Strings::startsWith with str_starts_with.',
            [
                new CodeSample(
                    // Code before
                    '$result = Strings::startsWith($haystack, $needle);',
                    // Code after
                    '$result = str_starts_with($haystack, $needle);',
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

        if (!$this->isName($node->name, 'startsWith')) {
            return null;
        }

        return $this->nodeFactory->createFuncCall('str_starts_with', $node->args);
    }


    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::STR_STARTS_WITH;
    }
}
