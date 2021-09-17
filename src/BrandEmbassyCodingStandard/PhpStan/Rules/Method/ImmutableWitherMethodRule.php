<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\FindingVisitor;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use function preg_match;
use function sprintf;

/**
 * @implements Rule<ClassMethod>
 */
class ImmutableWitherMethodRule implements Rule
{
    private const MESSAGE = 'Method %s() is a mutable wither as %s. The method should return modified clone of $this.';

    /**
     * @var NodeTraverser
     */
    private $nodeTraverser;

    /**
     * @var FindingVisitor
     */
    private $findingVisitor;


    public function __construct()
    {
        $this->nodeTraverser = new NodeTraverser();
        $this->findingVisitor = new FindingVisitor(function (Node $node): bool {
            return true;
        });

        $this->nodeTraverser->addVisitor($this->findingVisitor);
    }


    public function getNodeType(): string
    {
        return ClassMethod::class;
    }


    /**
     * @param ClassMethod $node
     *
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $methodName = $node->name->name;

        if (preg_match('~^with[A-Z0-9]~', $methodName) === 0) {
            return [];
        }

        if ($node->stmts === null) {
            return [];
        }

        $this->nodeTraverser->traverse($node->stmts);

        foreach ($this->findingVisitor->getFoundNodes() as $innerNode) {
            if ($this->isReturnThisNode($innerNode)) {
                return [sprintf(self::MESSAGE, $methodName, 'it returns $this')];
            }
        }

        return [];
    }


    private function isReturnThisNode(Node $node): bool
    {
        if (!$node instanceof Return_) {
            return false;
        }

        if ($node->expr instanceof Variable && $node->expr->name === 'this') {
            return true;
        }

        return false;
    }
}
