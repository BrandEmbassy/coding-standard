<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function assert;
use function class_exists;

/**
 * @final
 *
 * Not all methods are implemented because they have no equivalent in the native PHP enum.
 * SomeEnum::getValues() is not implemented because it is not possible to get all enum values in PHP.
 * array_column(SomeEnum::cases(), 'value') can be used instead.
 *
 * If we use only constants from enum (eg: $var = $bool? SomeEnum::USER: SomeEnum::SUPERUSER), they become objects in case of native enums, and it is necessary to add the call omeEnum::USER->value: SomeEnum::SUPERUSER->value or $var->value.
 * If the Enum is created from a variable, and this variable is a value of the const from enum, it is necessary to manually modify the created enum.
 * Eg: $var = SomeEnum::USER; $enum = SomeEnum::get($var); -> $enum = SomeEnum::USER;
 */
class MabeEnumMethodCallToEnumConstRector extends AbstractRector implements MinPhpVersionInterface, ConfigurableRectorInterface
{
    public const ARE_CLASSES_FROM_VENDOR_IGNORED = 'areClassesFromVendorIgnored';

    private const MABE_ENUM_CLASS_NAME = 'MabeEnum\\Enum';

    /**
     * @var array<string, bool>
     */
    private array $configuration = [];

    private MabeEnumFactory $mabeEnumFactory;


    public function __construct(MabeEnumFactory $mabeEnumFactory)
    {
        $this->mabeEnumFactory = $mabeEnumFactory;
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor Mabe enum fetch to Enum const', [
            new CodeSample(
                '$enum = SomeEnum::get(SomeEnum::VALUE);',
                '$enum = SomeEnum::VALUE;',
            ),
            new CodeSample(
                '$enum->getValue()',
                '$enum->value',
            ),
            new CodeSample(
                '$enum->getName()',
                '$enum->name',
            ),
            new CodeSample(
                '$enum->is(SomeEnum::VALUE)',
                '$enum === SomeEnum::VALUE',
            ),
            // If the enum is from another package, it can be ignored, ig. vendor/BrandEmbassy/...
            new ConfiguredCodeSample(
                'IgnoredEnum::getValue()',
                'IgnoredEnum::getValue()',
                [
                    self::ARE_CLASSES_FROM_VENDOR_IGNORED => false,
                ],
            ),
        ]);
    }


    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            MethodCall::class,
            StaticCall::class,
        ];
    }


    public function refactor(Node $node)
    {
        // Skip if MabeEnum is not part of the project
        if (!class_exists(self::MABE_ENUM_CLASS_NAME)) {
            return null;
        }

        assert($node instanceof MethodCall || $node instanceof StaticCall);
        $areClassesFromVendorIgnored = $this->configuration[self::ARE_CLASSES_FROM_VENDOR_IGNORED] ?? true;

        return $this->mabeEnumFactory->createFromNode($node, $areClassesFromVendorIgnored);
    }


    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }


    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->configuration = [self::ARE_CLASSES_FROM_VENDOR_IGNORED => true];
    }
}
