<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Skipper\Matcher\FileInfoMatcher;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;
use function assert;
use function in_array;

/**
 * The rule is not intended to be included in Rector indefinitely.
 * It is intended for a one-time run, and then you should rely on DisallowConstantsInTestsRule PHPStan rule.
 */
class DisallowConstantsInTestsRector extends AbstractRector implements ConfigurableRectorInterface
{
    private const ALLOWED_PATTERNS = 'allowedPatterns';

    private const ALLOWED_CONSTANTS = 'allowedConstants';

    /**
     * @var array<string>
     */
    private array $allowedPatterns = [];

    /**
     * @var array<string>
     */
    private array $allowedConstants = [];


    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly FileInfoMatcher $fileInfoMatcher,
    ) {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace production constants with literals in test files',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
final class Foo
{
    public function run()
    {
        $bar = SomeConstant::BAR;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class Foo
{
    public function run()
    {
        $bar = 'bar';
    }
}
CODE_SAMPLE
                    ,
                    [
                        self::ALLOWED_PATTERNS => ['vendor/*', 'tests/*', '*Fixture.php'],
                        self::ALLOWED_CONSTANTS => ['Project\Http\HttpMethod'],
                    ],
                ),
            ],
        );
    }


    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }


    public function refactor(Node $node)
    {
        assert($node instanceof ClassConstFetch);

        if (!$node->class instanceof Name) {
            return null;
        }

        if (!$node->name instanceof Identifier) {
            return null;
        }

        $scope = $node->getAttribute('scope');
        if (!$scope instanceof Scope) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return null;
        }

        $nodeClassReflection = $this->reflectionProvider->getClass($classReflection->getName());

        $className = $this->getName($node->class);

        if (!$nodeClassReflection->is('\PHPUnit\Framework\TestCase')) {
            return null;
        }

        if ($className === 'self') {
            return null;
        }

        $constantName = $this->getName($node->name);

        if ($constantName === 'class') {
            return null;
        }

        if ($constantName === null) {
            return null;
        }

        $constantClassReflection = $this->reflectionProvider->getClass($className);

        if (in_array($constantClassReflection->getName(), $this->allowedConstants, true)) {
            return null;
        }

        if ($this->fileInfoMatcher->doesFileInfoMatchPatterns(
            $constantClassReflection->getFileName() ?? '',
            $this->allowedPatterns,
        )) {
            return null;
        }

        if (!$constantClassReflection->hasConstant($constantName)) {
            return null;
        }

        if ($constantClassReflection->isEnum()) {
            return null;
        }

        return $constantClassReflection->getConstant($constantName)
            ->getValueExpr();
    }


    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        if (isset($configuration[self::ALLOWED_PATTERNS])) {
            Assert::isArray($configuration[self::ALLOWED_PATTERNS]);
            Assert::allString($configuration[self::ALLOWED_PATTERNS]);
            $this->allowedPatterns = $configuration[self::ALLOWED_PATTERNS];
            unset($configuration[self::ALLOWED_PATTERNS]);
        }

        if (isset($configuration[self::ALLOWED_CONSTANTS])) {
            Assert::isArray($configuration[self::ALLOWED_CONSTANTS]);
            Assert::allString($configuration[self::ALLOWED_CONSTANTS]);
            $this->allowedConstants = $configuration[self::ALLOWED_CONSTANTS];
            unset($configuration[self::ALLOWED_CONSTANTS]);
        }
    }
}
