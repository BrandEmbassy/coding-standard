<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Doctrine\ORM;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\ShouldNotHappenException;
use function sprintf;
use function strpos;

/**
 * @implements Rule<InClassNode>
 */
class EntityNotFinalRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }


    /**
     * @return array<string>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            throw new ShouldNotHappenException();
        }

        if (!$classReflection->isFinalByKeyword()) {
            return [];
        }

        $phpDoc = $classReflection->getResolvedPhpDoc();

        if ($phpDoc === null) {
            return [];
        }

        return $this->resolveBasedOnEntityAnnotation($phpDoc, $classReflection);
    }


    /**
     * @return array<string>
     */
    private function resolveBasedOnEntityAnnotation(
        ResolvedPhpDocBlock $phpDoc,
        ClassReflection $classReflection
    ): array {
        foreach ($phpDoc->getPhpDocNodes() as $phpDocNode) {
            foreach ($phpDocNode->getTagsByName('@ORM') as $phpDocTag) {
                if (strpos((string)$phpDocTag, '@ORM \Entity(') === 0) {
                    return [
                        sprintf(
                            'Entity class %s is final which can cause problems with proxies.',
                            $classReflection->getDisplayName()
                        ),
                    ];
                }
            }
        }

        return [];
    }
}
