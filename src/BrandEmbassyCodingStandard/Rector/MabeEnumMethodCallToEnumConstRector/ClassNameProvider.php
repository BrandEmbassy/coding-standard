<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Type\TypeWithClassName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

/**
 * @final
 */
class ClassNameProvider
{
    public function __construct(
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }


    /**
     * @return class-string|null
     */
    public function getClassNameFromNode(Node $node): ?string
    {
        $type = $this->nodeTypeResolver->getType($node);

        if (!$type instanceof TypeWithClassName) {
            return null;
        }

        /** @var class-string $className */
        $className = $type->getClassName();

        return $className;
    }


    /**
     * @return class-string|null
     */
    public function getClassNameFromClass(Name $node): ?string
    {
        if ($node->isFullyQualified()) {
            /** @var class-string|null $name */
            $name = $this->nodeNameResolver->getName($node);

            return $name;
        }

        return $this->getClassNameFromNode($node);
    }
}
