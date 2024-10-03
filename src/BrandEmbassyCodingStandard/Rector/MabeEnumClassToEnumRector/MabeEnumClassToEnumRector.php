<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\MabeEnumClassToEnumRector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Php81\NodeFactory\EnumFactory;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function assert;
use function class_exists;

/**
 * @final
 */
class MabeEnumClassToEnumRector extends AbstractRector implements MinPhpVersionInterface
{
    public const MABE_ENUM_CLASS_NAME = 'MabeEnum\\Enum';

    /**
     * @readonly
     */
    private EnumFactory $enumFactory;


    public function __construct(EnumFactory $enumFactory)
    {
        $this->enumFactory = $enumFactory;
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor MabeEnum enum class to native Enum', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use MabeEnum\Enum;

final class Action extends Enum`
{
    private const VIEW = 'view';
    private const EDIT = 'edit';
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
enum Action : string
{
    case VIEW = 'view';
    case EDIT = 'edit';
}
CODE_SAMPLE
                ,
            ),
        ]);
    }


    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }


    public function refactor(Node $node)
    {
        assert($node instanceof Class_);

        // Skip if MabeEnum is not part of the project
        if (!class_exists(self::MABE_ENUM_CLASS_NAME)) {
            return null;
        }

        if (!$this->isObjectType($node, new ObjectType('MabeEnum\Enum'))) {
            return null;
        }

        return $this->enumFactory->createFromClass($node);
    }


    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }
}
