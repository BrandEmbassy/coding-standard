<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\DoctrineEntitiesCannotBeFinalRector;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function assert;

/**
 * @final
 */
class DoctrineEntitiesCannotBeFinalRector extends AbstractRector
{
    private const DOCTRINE_ENTITY_CLASS = 'Doctrine\ORM\Mapping\Entity';


    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove final keyword from Doctrine entity classes, as final classes cannot be proxied by Doctrine.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class User
{
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
}
CODE_SAMPLE
                    ,
                ),
            ],
        );
    }


    public function getNodeTypes(): array
    {
        return [Class_::class];
    }


    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Class_);

        if (!$node->isFinal()) {
            return null;
        }

        if (!$this->isDoctrineEntity($node)) {
            return null;
        }

        $node->flags &= ~Modifiers::FINAL;

        return $node;
    }


    private function isDoctrineEntity(Class_ $class): bool
    {
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($this->isName($attr->name, self::DOCTRINE_ENTITY_CLASS)) {
                    return true;
                }
            }
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($class);

        return $phpDocInfo !== null && $phpDocInfo->hasByAnnotationClass(self::DOCTRINE_ENTITY_CLASS);
    }
}
