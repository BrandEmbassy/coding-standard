<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\FindingVisitor;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use function preg_match;
use function sprintf;
use function substr;

/**
 * @implements Rule<ClassMethod>
 */
class ImmutableWitherMethodRule implements Rule
{
    private const MESSAGE = 'Method %s() is a mutable wither as %s. The method should return modified clone of $this.';

    private NodeTraverser $nodeTraverser;

    private FindingVisitor $findingVisitor;


    public function __construct()
    {
        $this->nodeTraverser = new NodeTraverser();
        $this->findingVisitor = new FindingVisitor(fn(Node $node): bool => true);

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
            if ($this->isCallOfSetterOnThis($innerNode)) {
                return [sprintf(self::MESSAGE, $methodName, 'it calls a setter on $this')];
            }
            if ($this->isWriteToOwnProperty($innerNode)) {
                return [sprintf(self::MESSAGE, $methodName, 'it writes to own property')];
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


    private function isCallOfSetterOnThis(Node $node): bool
    {
        if (!$node instanceof MethodCall) {
            return false;
        }

        $isCallOnThis = $node->var instanceof Variable && $node->var->name === 'this';
        $isCallOfSetter = $node->name instanceof Identifier && substr($node->name->name, 0, 3) === 'set';

        return $isCallOnThis && $isCallOfSetter;
    }


    private function isWriteToOwnProperty(Node $node): bool
    {
        if (!$node instanceof Assign) {
            return false;
        }

        if (!$node->var instanceof PropertyFetch) {
            return false;
        }

        return $node->var->var instanceof Variable && $node->var->var->name === 'this';
    }
}
