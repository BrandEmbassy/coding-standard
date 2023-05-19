<?php declare(strict_types = 1);

use BrandEmbassyCodingStandard\Sniffs\Classes\ClassesWithoutSelfReferencingSniff;
use BrandEmbassyCodingStandard\Sniffs\Classes\FinalClassByAnnotationSniff;
use BrandEmbassyCodingStandard\Sniffs\Classes\TraitUsePositionSniff;
use BrandEmbassyCodingStandard\Sniffs\Classes\TraitUseSpacingSniff;
use BrandEmbassyCodingStandard\Sniffs\Commenting\FunctionCommentSniff;
use BrandEmbassyCodingStandard\Sniffs\WhiteSpace\BlankLineBeforeReturnSniff;
use BrandEmbassyCodingStandard\Sniffs\WhiteSpace\BlankLineBeforeThrowSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\ArrayIndentSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\DisallowLongArraySyntaxSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Classes\DuplicateClassNameSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\EmptyStatementSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\ForLoopWithTestFunctionCallSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\JumbledIncrementerSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UnconditionalIfStatementSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UnnecessaryFinalModifierSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UnusedFunctionParameterSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UselessOverridingMethodSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\InlineHTMLSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\OneObjectStructurePerFileSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\NoSpaceAfterCastSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\CharacterBeforePHPOpeningTagSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\DeprecatedFunctionsSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\LowerCaseConstantSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\LowerCaseTypeSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\NoSilencedErrorsSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\SAPIUsageSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Strings\UnnecessaryStringConcatSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\VersionControl\GitMergeConflictSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\ArbitraryParenthesesSpacingSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\DisallowTabIndentSniff;
use PHP_CodeSniffer\Standards\PEAR\Sniffs\Commenting\InlineCommentSniff;
use PHP_CodeSniffer\Standards\PEAR\Sniffs\WhiteSpace\ScopeIndentSniff;
use PHP_CodeSniffer\Standards\PSR1\Sniffs\Methods\CamelCapsMethodNameSniff;
use PHP_CodeSniffer\Standards\PSR12\Sniffs\Files\DeclareStatementSniff;
use PHP_CodeSniffer\Standards\PSR12\Sniffs\Files\FileHeaderSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ControlStructureSpacingSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Files\EndFileNewlineSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\MethodDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Arrays\ArrayBracketSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Arrays\ArrayDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ClassFileNameSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\LowercaseClassKeywordsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\SelfMemberReferenceSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ValidClassNameSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\DocCommentAlignmentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\EmptyCatchCommentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\VariableCommentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\FunctionDeclarationArgumentSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\GlobalFunctionSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\LowercaseFunctionKeywordsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\MultiLineFunctionDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Operators\ValidLogicalOperatorsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\CommentedOutCodeSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\GlobalKeywordSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\InnerFunctionsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\LowercasePHPFunctionsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\NonExecutableCodeSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Scope\StaticThisUsageSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Strings\ConcatenationSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Strings\DoubleQuoteUsageSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\FunctionOpeningBraceSpaceSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\FunctionSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\LanguageConstructSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\MemberVarSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\ObjectOperatorSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SemicolonSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SuperfluousWhitespaceSniff;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrimArraySpacesFixer;
use PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer;
use PhpCsFixer\Fixer\Basic\BracesFixer;
use PhpCsFixer\Fixer\Basic\CurlyBracesPositionFixer;
use PhpCsFixer\Fixer\Basic\EncodingFixer;
use PhpCsFixer\Fixer\Basic\NoMultipleStatementsPerLineFixer;
use PhpCsFixer\Fixer\Basic\NoTrailingCommaInSinglelineFixer;
use PhpCsFixer\Fixer\Casing\ConstantCaseFixer;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\CastNotation\LowercaseCastFixer;
use PhpCsFixer\Fixer\CastNotation\ShortScalarCastFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\NoBlankLinesAfterClassOpeningFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SingleClassElementPerStatementFixer;
use PhpCsFixer\Fixer\ClassNotation\SingleTraitInsertPerStatementFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Comment\NoTrailingWhitespaceInCommentFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureContinuationPositionFixer;
use PhpCsFixer\Fixer\ControlStructure\ElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\NoBreakCommentFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededControlParenthesesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededCurlyBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSemicolonToColonFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSpaceFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\DoctrineAnnotation\DoctrineAnnotationArrayAssignmentFixer;
use PhpCsFixer\Fixer\DoctrineAnnotation\DoctrineAnnotationIndentationFixer;
use PhpCsFixer\Fixer\DoctrineAnnotation\DoctrineAnnotationSpacesFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionTypehintSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoSpacesAfterFunctionNameFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer;
use PhpCsFixer\Fixer\Import\SingleLineAfterImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareParenthesesFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ExplicitIndirectVariableFixer;
use PhpCsFixer\Fixer\LanguageConstruct\FunctionToConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\IsNullFixer;
use PhpCsFixer\Fixer\LanguageConstruct\SingleSpaceAfterConstructFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLineAfterNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\NoLeadingNamespaceWhitespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\SingleBlankLineBeforeNamespaceFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\NewWithBracesFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Operator\StandardizeIncrementFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocIndentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoEmptyReturnFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocReturnSelfReferenceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSingleLineVarSpacingFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimConsecutiveBlankLineSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocVarWithoutNameFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\FullOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\NoClosingTagFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\SpaceAfterSemicolonFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use PhpCsFixer\Fixer\Whitespace\LineEndingFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesInsideParenthesisFixer;
use PhpCsFixer\Fixer\Whitespace\NoTrailingWhitespaceFixer;
use PhpCsFixer\Fixer\Whitespace\NoWhitespaceInBlankLineFixer;
use PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer;
use PhpCsFixer\Fixer\Whitespace\StatementIndentationFixer;
use SlevomatCodingStandard\Sniffs\Arrays\DisallowImplicitArrayCreationSniff;
use SlevomatCodingStandard\Sniffs\Arrays\TrailingArrayCommaSniff;
use SlevomatCodingStandard\Sniffs\Classes\ClassConstantVisibilitySniff;
use SlevomatCodingStandard\Sniffs\Classes\EmptyLinesAroundClassBracesSniff;
use SlevomatCodingStandard\Sniffs\Classes\TraitUseDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff;
use SlevomatCodingStandard\Sniffs\Commenting\EmptyCommentSniff;
use SlevomatCodingStandard\Sniffs\Commenting\ForbiddenAnnotationsSniff;
use SlevomatCodingStandard\Sniffs\Commenting\ForbiddenCommentsSniff;
use SlevomatCodingStandard\Sniffs\Commenting\UselessFunctionDocCommentSniff;
use SlevomatCodingStandard\Sniffs\Commenting\UselessInheritDocCommentSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowYodaComparisonSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\LanguageConstructWithParenthesesSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\RequireNullCoalesceOperatorSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\RequireShortTernaryOperatorSniff;
use SlevomatCodingStandard\Sniffs\Exceptions\DeadCatchSniff;
use SlevomatCodingStandard\Sniffs\Exceptions\ReferenceThrowableOnlySniff;
use SlevomatCodingStandard\Sniffs\Functions\ArrowFunctionDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Functions\RequireArrowFunctionSniff;
use SlevomatCodingStandard\Sniffs\Functions\RequireTrailingCommaInCallSniff;
use SlevomatCodingStandard\Sniffs\Functions\UnusedInheritedVariablePassedToClosureSniff;
use SlevomatCodingStandard\Sniffs\Functions\UselessParameterDefaultValueSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\DisallowGroupUseSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\MultipleUsesPerLineSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\ReferenceUsedNamesOnlySniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UnusedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseDoesNotStartWithBackslashSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseFromSameNamespaceSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UselessAliasSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff;
use SlevomatCodingStandard\Sniffs\Operators\DisallowEqualOperatorsSniff;
use SlevomatCodingStandard\Sniffs\Operators\RequireCombinedAssignmentOperatorSniff;
use SlevomatCodingStandard\Sniffs\PHP\TypeCastSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\NullableTypeForNullDefaultValueSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\NullTypeHintOnLastPositionSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSpacingSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSpacingSniff;
use SlevomatCodingStandard\Sniffs\Variables\UnusedVariableSniff;
use Symplify\CodingStandard\Fixer\Annotation\RemovePHPStormAnnotationFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\Commenting\ParamReturnAndVarTagMalformsFixer;
use Symplify\CodingStandard\Fixer\Commenting\RemoveUselessDefaultCommentFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\CodingStandard\Fixer\Spacing\SpaceAfterCommaHereNowDocFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLinePromotedPropertyFixer;
use Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig, string $projectRootPath): array {
    // TODO: Switch to set lists once rules stabilize
    // $ecsConfig->sets([
    //     SetList::CLEAN_CODE,
    //     SetList::SYMPLIFY,
    //     SetList::COMMON,
    //     SetList::PSR_12,
    //     SetList::DOCTRINE_ANNOTATIONS,
    // ]);

    // region SetList::CLEAN_CODE
    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);
    $ecsConfig->rules(
        [
            ParamReturnAndVarTagMalformsFixer::class,
            NoUnusedImportsFixer::class,
            OrderedImportsFixer::class,
            NoEmptyStatementFixer::class,
            ProtectedToPrivateFixer::class,
            NoUnneededControlParenthesesFixer::class,
            NoUnneededCurlyBracesFixer::class,
        ],
    );
    // endregion SetList::CLEAN_CODE

    // region SetList::SYMPLIFY
    $ecsConfig->import(
        $projectRootPath . '/vendor/symplify/easy-coding-standard/vendor/symplify/coding-standard/config/config.php',
    );
    // explicit like other configs :) no magic!!!
    $ecsConfig->rules([
        // docblocks and comments
        RemovePHPStormAnnotationFixer::class,
        ParamReturnAndVarTagMalformsFixer::class,
        RemoveUselessDefaultCommentFixer::class,
        // arrays
        ArrayListItemNewlineFixer::class,
        ArrayOpenerAndCloserNewlineFixer::class,
        StandaloneLinePromotedPropertyFixer::class,
        // newlines
        MethodChainingNewlineFixer::class,
        SpaceAfterCommaHereNowDocFixer::class,
        BlankLineAfterStrictTypesFixer::class,
        // line length
        LineLengthFixer::class,
    ]);
    $ecsConfig->ruleWithConfiguration(
        GeneralPhpdocAnnotationRemoveFixer::class,
        [
            'annotations' => ['throws', 'author', 'package', 'group', 'covers', 'category'],
        ],
    );
    // endregion SetList::SYMPLIFY

    // region SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/array.php
    $ecsConfig->rules(
        [
            NoWhitespaceBeforeCommaInArrayFixer::class,
            ArrayOpenerAndCloserNewlineFixer::class,
            ArrayIndentationFixer::class,
            TrimArraySpacesFixer::class,
            WhitespaceAfterCommaInArrayFixer::class,
            ArrayListItemNewlineFixer::class,
            StandaloneLineInMultilineArrayFixer::class,
        ],
    );
    // commas
    $ecsConfig->ruleWithConfiguration(
        NoTrailingCommaInSinglelineFixer::class,
        [
            'elements' => ['arguments', 'array_destructuring', 'array', 'group_import'],
        ],
    );
    $ecsConfig->ruleWithConfiguration(
        TrailingCommaInMultilineFixer::class,
        [
            'elements' => [TrailingCommaInMultilineFixer::ELEMENTS_ARRAYS],
        ],
    );
    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);
    // endregion SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/array.php

    // region SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/comments.php
    $ecsConfig->rule(GitMergeConflictSniff::class);
    // endregion SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/comments.php

    // region SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/control-structures.php
    $ecsConfig->rules(
        [
            PhpUnitMethodCasingFixer::class,
            FunctionToConstantFixer::class,
            ExplicitStringVariableFixer::class,
            ExplicitIndirectVariableFixer::class,
            NewWithBracesFixer::class,
            StandardizeIncrementFixer::class,
            SelfAccessorFixer::class,
            MagicConstantCasingFixer::class,
            AssignmentInConditionSniff::class,
            NoUselessElseFixer::class,
            SingleQuoteFixer::class,
            OrderedClassElementsFixer::class,
        ],
    );
    $ecsConfig->ruleWithConfiguration(
        SingleClassElementPerStatementFixer::class,
        [
            'elements' => ['const', 'property'],
        ],
    );
    $ecsConfig->ruleWithConfiguration(ClassDefinitionFixer::class, [
        'single_line' => true,
    ]);
    $ecsConfig->ruleWithConfiguration(
        YodaStyleFixer::class,
        [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
    );
    // endregion SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/control-structures.php

    // region SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/docblock.php
    $ecsConfig->rules(
        [
            PhpdocLineSpanFixer::class,
            NoTrailingWhitespaceInCommentFixer::class,
            PhpdocTrimConsecutiveBlankLineSeparationFixer::class,
            PhpdocTrimFixer::class,
            NoEmptyPhpdocFixer::class,
            PhpdocNoEmptyReturnFixer::class,
            PhpdocIndentFixer::class,
            PhpdocTypesFixer::class,
            PhpdocReturnSelfReferenceFixer::class,
            PhpdocVarWithoutNameFixer::class,
            RemoveUselessDefaultCommentFixer::class,
        ],
    );
    $ecsConfig->ruleWithConfiguration(
        NoSuperfluousPhpdocTagsFixer::class,
        [
            'remove_inheritdoc' => true,
            'allow_mixed' => true,
        ],
    );
    // endregion SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/docblock.php

    // region SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/phpunit.php
    $ecsConfig->rules([
        NoUnusedImportsFixer::class,
        OrderedImportsFixer::class,
        SingleBlankLineBeforeNamespaceFixer::class,
    ]);
    // endregion SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/phpunit.php

    // region SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/namespaces.php
    $ecsConfig->rules([PhpUnitTestAnnotationFixer::class, PhpUnitSetUpTearDownVisibilityFixer::class]);
    // endregion SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/namespaces.php

    // region SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/spaces.php
    $ecsConfig->rules(
        [
            StandaloneLinePromotedPropertyFixer::class,
            BlankLineAfterOpeningTagFixer::class,
            MethodChainingIndentationFixer::class,
            NotOperatorWithSuccessorSpaceFixer::class,
            CastSpacesFixer::class,
            ClassAttributesSeparationFixer::class,
            SingleTraitInsertPerStatementFixer::class,
            FunctionTypehintSpaceFixer::class,
            NoBlankLinesAfterClassOpeningFixer::class,
            NoSinglelineWhitespaceBeforeSemicolonsFixer::class,
            PhpdocSingleLineVarSpacingFixer::class,
            NoLeadingNamespaceWhitespaceFixer::class,
            NoSpacesAroundOffsetFixer::class,
            NoWhitespaceInBlankLineFixer::class,
            ReturnTypeDeclarationFixer::class,
            SpaceAfterSemicolonFixer::class,
            TernaryOperatorSpacesFixer::class,
            MethodArgumentSpaceFixer::class,
            LanguageConstructSpacingSniff::class,
        ],
    );
    $ecsConfig->ruleWithConfiguration(
        ClassAttributesSeparationFixer::class,
        [
            'elements' => [
                'const' => 'one',
                'property' => 'one',
                'method' => 'one',
            ],
        ],
    );
    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, [
        'spacing' => 'one',
    ]);
    $ecsConfig->ruleWithConfiguration(SuperfluousWhitespaceSniff::class, [
        'ignoreBlankLines' => false,
    ]);
    $ecsConfig->ruleWithConfiguration(
        BinaryOperatorSpacesFixer::class,
        [
            'operators' => [
                '=>' => 'single_space',
                '=' => 'single_space',
            ],
        ],
    );
    // endregion SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/spaces.php

    // region SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/strict.php
    $ecsConfig->rules([
        StrictComparisonFixer::class,
        IsNullFixer::class,
        StrictParamFixer::class,
        DeclareStrictTypesFixer::class,
    ]);
    // endregion SetList::COMMON - vendor/symplify/easy-coding-standard/config/set/common/strict.php

    // region SetList::PSR_12 - vendor/symplify/easy-coding-standard/config/set/psr12.php
    $ecsConfig->ruleWithConfiguration(OrderedImportsFixer::class, [
        'imports_order' => ['class', 'function', 'const'],
    ]);
    $ecsConfig->ruleWithConfiguration(DeclareEqualNormalizeFixer::class, [
        'space' => 'none',
    ]);
    $ecsConfig->ruleWithConfiguration(
        BracesFixer::class,
        [
            'allow_single_line_closure' => false,
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_control_structures' => 'same',
            'position_after_anonymous_constructs' => 'same',
        ],
    );
    // split of BracesFixer in PHP CS Fixer 3.10 - https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/4884
    $ecsConfig->rules(
        [
            ControlStructureBracesFixer::class,
            CurlyBracesPositionFixer::class,
            NoMultipleStatementsPerLineFixer::class,
            DeclareParenthesesFixer::class,
            ControlStructureContinuationPositionFixer::class,
            StatementIndentationFixer::class,
            SingleSpaceAfterConstructFixer::class,
        ],
    );
    $ecsConfig->ruleWithConfiguration(VisibilityRequiredFixer::class, [
        'elements' => ['const', 'method', 'property'],
    ]);
    $ecsConfig->rules([
        BinaryOperatorSpacesFixer::class,
        BlankLineAfterNamespaceFixer::class,
        BlankLineAfterOpeningTagFixer::class,
        ClassDefinitionFixer::class,
        ConstantCaseFixer::class,
        ElseifFixer::class,
        EncodingFixer::class,
        FullOpeningTagFixer::class,
        FunctionDeclarationFixer::class,
        IndentationTypeFixer::class,
        LineEndingFixer::class,
        LowercaseCastFixer::class,
        LowercaseKeywordsFixer::class,
        NewWithBracesFixer::class,
        NoBlankLinesAfterClassOpeningFixer::class,
        NoBreakCommentFixer::class,
        NoClosingTagFixer::class,
        NoLeadingImportSlashFixer::class,
        NoSinglelineWhitespaceBeforeSemicolonsFixer::class,
        NoSpacesAfterFunctionNameFixer::class,
        NoSpacesInsideParenthesisFixer::class,
        NoTrailingWhitespaceFixer::class,
        NoTrailingWhitespaceInCommentFixer::class,
        NoWhitespaceBeforeCommaInArrayFixer::class,
        ReturnTypeDeclarationFixer::class,
        ShortScalarCastFixer::class,
        SingleBlankLineAtEofFixer::class,
        SingleImportPerStatementFixer::class,
        SingleLineAfterImportsFixer::class,
        SwitchCaseSemicolonToColonFixer::class,
        SwitchCaseSpaceFixer::class,
        TernaryOperatorSpacesFixer::class,
        UnaryOperatorSpacesFixer::class,
        VisibilityRequiredFixer::class,
        WhitespaceAfterCommaInArrayFixer::class,
    ]);
    $ecsConfig->ruleWithConfiguration(MethodArgumentSpaceFixer::class, [
        'on_multiline' => 'ensure_fully_multiline',
    ]);
    $ecsConfig->ruleWithConfiguration(SingleClassElementPerStatementFixer::class, [
        'elements' => ['property'],
    ]);
    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, [
        'spacing' => 'one',
    ]);
    $ecsConfig->skip([SingleImportPerStatementFixer::class]);
    // endregion SetList::PSR_12 - vendor/symplify/easy-coding-standard/config/set/psr12.php

    // region SetList::DOCTRINE_ANNOTATIONS - vendor/symplify/easy-coding-standard/config/set/doctrine-annotations.php
    $ecsConfig->ruleWithConfiguration(DoctrineAnnotationIndentationFixer::class, [
        'indent_mixed_lines' => true,
    ]);
    $ecsConfig->ruleWithConfiguration(
        DoctrineAnnotationSpacesFixer::class,
        [
            'after_array_assignments_equals' => false,
            'before_array_assignments_equals' => false,
        ],
    );
    $ecsConfig->rule(DoctrineAnnotationArrayAssignmentFixer::class);
    // endregion SetList::DOCTRINE_ANNOTATIONS - vendor/symplify/easy-coding-standard/config/set/doctrine-annotations.php

    // region === brand embassy coding standard ===
    $ecsConfig->rule(LineLengthFixer::class);
    $ecsConfig->ruleWithConfiguration(PhpdocLineSpanFixer::class, [
        'const' => 'single',
    ]);
    // Forbid duplicate classes
    $ecsConfig->rule(DuplicateClassNameSniff::class);
    // Forbid `array(...)`
    $ecsConfig->rule(DisallowLongArraySyntaxSniff::class);
    // Require array element indentation with 4 spaces
    // TODO: Remove in favor of ArrayIndentationFixer after discussion + conflicts with ArrayDeclarationSniff
    $ecsConfig->rule(ArrayIndentSniff::class);
    $ecsConfig->rule(EmptyStatementSniff::class);
    // TODO: Conflicts with DeclareStrictTypesSniff, we should discuss which one to use
    $ecsConfig->rule(FileHeaderSniff::class);
    $ecsConfig->rule(DeclareStatementSniff::class);
    // Forbid using of functions in for test expression
    $ecsConfig->rule(ForLoopWithTestFunctionCallSniff::class);
    // Forbid one and the same incrementer in an outer and an inner loop
    $ecsConfig->rule(JumbledIncrementerSniff::class);
    // Forbid if (true) and if (false)
    $ecsConfig->rule(UnconditionalIfStatementSniff::class);
    // Forbid final methods in final classes
    $ecsConfig->rule(UnnecessaryFinalModifierSniff::class);
    // Forbid unused function parameters
    $ecsConfig->rule(UnusedFunctionParameterSniff::class);
    // Forbid overrides which only call their parent
    $ecsConfig->rule(UselessOverridingMethodSniff::class);
    // ECS intentionally does not allow severities, either rule should be required or not
    // $ecsConfig->rule(TodoSniff::class);
    // Forbid inline HTML in PHP code
    $ecsConfig->rule(InlineHTMLSniff::class);
    // Forbid more than one object structure (class, interface, trait) in a file
    $ecsConfig->rule(OneObjectStructurePerFileSniff::class);
    // Forbid space after cast // TODO: Remove in favor of CastSpacesFixer
    $ecsConfig->rule(NoSpaceAfterCastSniff::class);
    // Forbid any content before opening tag
    $ecsConfig->rule(CharacterBeforePHPOpeningTagSniff::class);
    // Forbid deprecated functions
    $ecsConfig->rule(DeprecatedFunctionsSniff::class);
    // Forbid alias functions, i.e. `sizeof()`, `delete()`
    $ecsConfig->ruleWithConfiguration(ForbiddenFunctionsSniff::class, [
        'forbiddenFunctions' => [
            'chop' => 'rtrim',
            'close' => 'closedir',
            'compact' => null,
            'delete' => 'unset',
            'doubleval' => 'floatval',
            'extract' => null,
            'fputs' => 'fwrite',
            'ini_alter' => 'ini_set',
            'is_double' => 'is_float',
            'is_integer' => 'is_int',
            'is_long' => 'is_int',
            'is_null' => null,
            'is_real' => 'is_float',
            'is_writeable' => 'is_writable',
            'join' => 'implode',
            'key_exists' => 'array_key_exists',
            'pos' => 'current',
            'settype' => null,
            'show_source' => 'highlight_file',
            'sizeof' => 'count',
            'strchr' => 'strstr',
            'var_dump' => null,
            'dump' => null,
            'empty' => null,
        ],
    ]);
    // Require true, false and null are lowercase
    $ecsConfig->rule(LowerCaseConstantSniff::class);
    // Require php types to be lowercase
    $ecsConfig->rule(LowerCaseTypeSniff::class);
    // Forbid silencing errors
    $ecsConfig->rule(NoSilencedErrorsSniff::class);
    // Forbid php_sapi_name() instead of PHP_SAPI constant
    $ecsConfig->rule(SAPIUsageSniff::class);
    // Forbid useless inline string concatenation
    $ecsConfig->ruleWithConfiguration(UnnecessaryStringConcatSniff::class, [
        // But multi-line is useful for readability
        'allowMultiline' => true,
    ]);
    // Forbid useless inline string concatenation
    $ecsConfig->ruleWithConfiguration(ArbitraryParenthesesSpacingSniff::class, [
        'ignoreNewlines' => true,
    ]);
    // Forbid tabs for indentation
    $ecsConfig->rule(DisallowTabIndentSniff::class);
    // Require space after language constructs
    $ecsConfig->rule(LanguageConstructSpacingSniff::class);
    // Require correct scope indent
    $ecsConfig->rule(ScopeIndentSniff::class);
    // Forbid comments starting with #
    $ecsConfig->rule(InlineCommentSniff::class);
    // Require strict types declaration and its format
    $ecsConfig->ruleWithConfiguration(DeclareStrictTypesSniff::class, [
        'declareOnFirstLine' => 1,
        'linesCountBeforeDeclare' => 0,
        'linesCountAfterDeclare' => 1,
        'spacesCountAroundEqualsSign' => 1,
    ]);
    // Require parameter type hints
    $ecsConfig->ruleWithConfiguration(ParameterTypeHintSniff::class, [
        'enableObjectTypeHint' => false,
        'traversableTypeHints' => [
            '\Doctrine\Common\Collections\ArrayCollection',
            '\Doctrine\Common\Collections\Collection',
        ],
    ]);
    // Require property type hints
    $ecsConfig->ruleWithConfiguration(PropertyTypeHintSniff::class, [
        'traversableTypeHints' => [
            '\Doctrine\Common\Collections\ArrayCollection',
            '\Doctrine\Common\Collections\Collection',
        ],
    ]);
    // Require return type hints
    $ecsConfig->ruleWithConfiguration(ReturnTypeHintSniff::class, [
        'enableObjectTypeHint' => false,
        'traversableTypeHints' => [
            '\Doctrine\Common\Collections\ArrayCollection',
            '\Doctrine\Common\Collections\Collection',
        ],
    ]);
    // Disallow useless function annotations
    $ecsConfig->ruleWithConfiguration(UselessFunctionDocCommentSniff::class, [
        'traversableTypeHints' => [
            '\Doctrine\Common\Collections\ArrayCollection',
            '\Doctrine\Common\Collections\Collection',
        ],
    ]);
    // Forbid assignment in if, elseif and do-while
    $ecsConfig->rule(AssignmentInConditionSniff::class);
    // Forbid == and !=
    $ecsConfig->rule(DisallowEqualOperatorsSniff::class);
    // Requires short ternary operator ?: when possible
    $ecsConfig->rule(RequireShortTernaryOperatorSniff::class);
    // Requires use of null coalesce operator when possible
    $ecsConfig->rule(RequireNullCoalesceOperatorSniff::class);
    // Forbid unused use statements
    $ecsConfig->ruleWithConfiguration(UnusedUsesSniff::class, [
        'searchAnnotations' => true,
    ]);
    // Forbid useless uses of the same namespace
    $ecsConfig->rule(UseFromSameNamespaceSniff::class);
    // Forbid useless unreachable catch blocks
    $ecsConfig->rule(DeadCatchSniff::class);
    // Require comma after last element in multi-line array
    $ecsConfig->rule(TrailingArrayCommaSniff::class);
    // Require language constructs without parentheses
    $ecsConfig->rule(LanguageConstructWithParenthesesSniff::class);
    // Forbid yoda conditions
    $ecsConfig->rule(DisallowYodaComparisonSniff::class);
    // Require use statements to be alphabetically sorted
    $ecsConfig->rule(AlphabeticallySortedUsesSniff::class);
    // Require empty newlines before and after uses
    $ecsConfig->ruleWithConfiguration(UseSpacingSniff::class, [
        'linesCountAfterLastUse' => 1,
        'linesCountBeforeFirstUse' => 1,
        'linesCountBetweenUseTypes' => 0,
    ]);
    // Forbid use of longhand cast operators
    $ecsConfig->rule(TypeCastSniff::class);
    // Require presence of constant visibility
    $ecsConfig->rule(ClassConstantVisibilitySniff::class);
    // Requires using combined assigment operators, eg +=, .= etc
    $ecsConfig->rule(RequireCombinedAssignmentOperatorSniff::class);
    // Enforces null type hint on last position in @var, @param and @return annotations
    $ecsConfig->rule(NullTypeHintOnLastPositionSniff::class);
    // Require space around colon in return types
    $ecsConfig->rule(ReturnTypeHintSpacingSniff::class);
    // Require ? when default value is null
    $ecsConfig->rule(NullableTypeForNullDefaultValueSniff::class);
    // Require one space between typehint and variable, require no space between nullability sign and typehint
    $ecsConfig->rule(ParameterTypeHintSpacingSniff::class);
    // Forbid group uses
    $ecsConfig->rule(DisallowGroupUseSniff::class);
    // Forbid multiple use statements on same line
    $ecsConfig->rule(MultipleUsesPerLineSniff::class);
    // Forbid using absolute class name references (except global ones)
    $ecsConfig->ruleWithConfiguration(ReferenceUsedNamesOnlySniff::class, [
        'allowFallbackGlobalConstants' => false,
        'allowFallbackGlobalFunctions' => false,
        'allowFullyQualifiedGlobalClasses' => false,
        'allowFullyQualifiedGlobalConstants' => false,
        'allowFullyQualifiedGlobalFunctions' => false,
        'allowFullyQualifiedNameForCollidingClasses' => true,
        'allowFullyQualifiedNameForCollidingConstants' => true,
        'allowFullyQualifiedNameForCollidingFunctions' => true,
        'searchAnnotations' => true,
    ]);
    // Forbid superfluous leading backslash in use statement
    $ecsConfig->rule(UseDoesNotStartWithBackslashSniff::class);
    // Forbid empty lines around type declarations
    $ecsConfig->ruleWithConfiguration(EmptyLinesAroundClassBracesSniff::class, [
        'linesCountAfterOpeningBrace' => 0,
        'linesCountBeforeClosingBrace' => 0,
    ]);
    // Forbid useless annotations - Git and LICENCE file provide more accurate information
    $ecsConfig->ruleWithConfiguration(ForbiddenAnnotationsSniff::class, [
        'forbiddenAnnotations' => [
            '@api',
            '@author',
            '@category',
            '@copyright',
            '@created',
            '@license',
            '@package',
            '@since',
            '@subpackage',
            '@version',
        ],
    ]);
    // Forbid useless comments
    $ecsConfig->ruleWithConfiguration(ForbiddenCommentsSniff::class, [
        'forbiddenCommentPatterns' => [
            '~^(?:(?!private|protected|static)\S+ )?(?:con|de)structor\.\z~i',
            '~^Created by \S+\.\z~i',
            '~^\S+ [gs]etter\.\z~i',
        ],
    ]);
    // Forbid empty comments
    $ecsConfig->rule(EmptyCommentSniff::class);
    // Reports documentation comments containing only {@inheritDoc} annotation because inheritance is automatic
    $ecsConfig->rule(UselessInheritDocCommentSniff::class);
    // Prohibits multiple traits separated by commas in one use statement
    $ecsConfig->rule(TraitUseDeclarationSniff::class);
    // Looks for unused variables
    $ecsConfig->ruleWithConfiguration(UnusedVariableSniff::class, [
        'ignoreUnusedValuesWhenOnlyKeysAreUsedInForeach' => true,
    ]);
    // Looks for useless parameter default value
    $ecsConfig->rule(UnusedInheritedVariablePassedToClosureSniff::class);
    // Looks for use alias that is same as unqualified name
    $ecsConfig->rule(UselessAliasSniff::class);
    // Prohibits multiple traits separated by commas in one use statement
    $ecsConfig->rule(UselessParameterDefaultValueSniff::class);
    // Requires arrow functions for one-line Closures
    $ecsConfig->rule(RequireArrowFunctionSniff::class);
    // Arrow function formatting
    $ecsConfig->ruleWithConfiguration(ArrowFunctionDeclarationSniff::class, [
        'spacesCountAfterKeyword' => 0,
    ]);
    // Requires trailing comma in multiline function calls
    $ecsConfig->rule(RequireTrailingCommaInCallSniff::class);
    // Disallows implicit array creation
    $ecsConfig->rule(DisallowImplicitArrayCreationSniff::class);
    // PHPDoc annotation formatting
    $ecsConfig->ruleWithConfiguration(DocCommentSpacingSniff::class, [
        'linesCountBetweenDifferentAnnotationsTypes' => 1,
        'annotationsGroups' => [
            '@deprecated',
            '@phpcsSuppress',
            '@dataProvider',
            '@property',
            '@method',
            '@var',
            '@param',
            '@ORM\\',
            '@return',
            '@throws',
        ],
    ]);
    // Forbid spacing after and before array brackets
    $ecsConfig->rule(ArrayBracketSpacingSniff::class);
    // Force array declaration structure
    $ecsConfig->rule(ArrayDeclarationSniff::class);
    // Forbid class being in a file with different name
    $ecsConfig->rule(ClassFileNameSniff::class);
    // Force class keyword to be lowercase
    $ecsConfig->rule(LowercaseClassKeywordsSniff::class);
    // Force `self::` for self-reference, force lower-case self, forbid spaces around `::`
    $ecsConfig->rule(SelfMemberReferenceSniff::class);
    // Ensures classes are in camel caps, and the first letter is capitalised
    $ecsConfig->rule(ValidClassNameSniff::class);
    // Force phpDoc alignment
    $ecsConfig->rule(DocCommentAlignmentSniff::class);
    // Forbid empty catch without comment
    $ecsConfig->rule(EmptyCatchCommentSniff::class);
    // Force rules for inline comments
    $ecsConfig->rule(InlineCommentSniff::class);
    // Force rules for variable comments
    $ecsConfig->rule(VariableCommentSniff::class);
    // Force rules for function argument spacing
    $ecsConfig->ruleWithConfiguration(FunctionDeclarationArgumentSpacingSniff::class, [
        'equalsSpacing' => 1,
    ]);
    // Forbid global functions
    $ecsConfig->rule(GlobalFunctionSniff::class);
    // Force function keyword to be lowercase
    $ecsConfig->rule(LowercaseFunctionKeywordsSniff::class);
    // Force function declarations to be defined correctly
    $ecsConfig->rule(MultiLineFunctionDeclarationSniff::class);
    // Forbid `AND` and `OR`, require `&&` and `||`
    $ecsConfig->rule(ValidLogicalOperatorsSniff::class);
    // Warn about commented out code
    $ecsConfig->rule(CommentedOutCodeSniff::class);
    // Forbid `global`
    $ecsConfig->rule(GlobalKeywordSniff::class);
    // Forbid functions inside functions
    $ecsConfig->rule(InnerFunctionsSniff::class);
    // Require PHP function calls in lowercase
    $ecsConfig->rule(LowercasePHPFunctionsSniff::class);
    // Forbid dead code
    $ecsConfig->rule(NonExecutableCodeSniff::class);
    // Forbid `$this` inside static function
    $ecsConfig->rule(StaticThisUsageSniff::class);
    // Force whitespace before and after concatenation
    $ecsConfig->ruleWithConfiguration(ConcatenationSpacingSniff::class, [
        'spacing' => 1,
        'ignoreNewlines' => true,
    ]);
    // Forbid strings in `"` unless necessary
    $ecsConfig->rule(DoubleQuoteUsageSniff::class);
    // Forbid blank line after function opening brace
    $ecsConfig->rule(FunctionOpeningBraceSpaceSniff::class);
    // Force correct function spacing
    $ecsConfig->ruleWithConfiguration(FunctionSpacingSniff::class, [
        'spacing' => 2,
        'spacingBeforeFirst' => 0,
        'spacingAfterLast' => 0,
    ]);
    // Force spacing around member variables
    $ecsConfig->ruleWithConfiguration(MemberVarSpacingSniff::class, [
        'spacing' => 1,
        'spacingBeforeFirst' => 0,
    ]);
    // Forbid spaces around `->` operator
    $ecsConfig->ruleWithConfiguration(ObjectOperatorSpacingSniff::class, [
        'ignoreNewlines' => true,
    ]);
    // Forbid spaces before semicolon `;`
    $ecsConfig->rule(SemicolonSpacingSniff::class);
    // Forbid superfluous whitespaces
    $ecsConfig->ruleWithConfiguration(SuperfluousWhitespaceSniff::class, [
        'ignoreBlankLines' => false,
    ]);
    // Force trait use as first statement in class
    $ecsConfig->rule(TraitUsePositionSniff::class);
    // Require empty newlines after uses
    $ecsConfig->ruleWithConfiguration(TraitUseSpacingSniff::class, [
        'linesCountBeforeFirstUse' => 0,
        'linesCountBetweenUses' => 0,
        'linesCountAfterLastUse' => 2,
        'linesCountAfterLastUseWhenLastInClass' => 0,
        'linesBeforeFollowingPropertyOrConstant' => 1,
    ]);
    // Forbid using self::assertX in TestCase
    $ecsConfig->ruleWithConfiguration(ClassesWithoutSelfReferencingSniff::class, [
        'classesWithoutSelfReferencing' => ['\PHPUnit\Framework\Assert'],
    ]);
    // Force final classes by annotation to allow proxies and mocks
    $ecsConfig->rule(FinalClassByAnnotationSniff::class);
    // Force blank line before return statement
    $ecsConfig->rule(BlankLineBeforeReturnSniff::class);
    // Force blank line before throw statement
    $ecsConfig->rule(BlankLineBeforeThrowSniff::class);
    // Force rules for function phpDoc
    $ecsConfig->rule(FunctionCommentSniff::class);

    // Forbid catching exceptions with Exception
    $ecsConfig->rule(ReferenceThrowableOnlySniff::class);

    // TODO: PSR2 rules have been deprecated https://www.php-fig.org/psr/psr-2/, we probably should not use any of them
    $ecsConfig->rule(ControlStructureSpacingSniff::class);
    $ecsConfig->rule(MethodDeclarationSniff::class);
    $ecsConfig->rule(EndFileNewlineSniff::class);

    // TODO: Discuss on dev sync, we are not aligned with PSR_12!
    $ecsConfig->ruleWithConfiguration(DeclareEqualNormalizeFixer::class, [
        // Override configuration from SetList::PSR_12
        'space' => 'single',
    ]);

    // TODO: Discuss on dev sync, we are not aligned with PSR_12? + responsibility overlap with ArrowFunctionDeclarationSniff
    $ecsConfig->ruleWithConfiguration(FunctionDeclarationFixer::class, [
        // Override PSR 12 in favor of none
        'closure_function_spacing' => 'one',
        'closure_fn_spacing' => 'none',
    ]);

    $ecsConfig->ruleWithConfiguration(CastSpacesFixer::class, [
        'space' => 'none',
    ]);

    // Override configuration from SetList::SYMPLIFY, we don't want to remove the @throws and @group annotations
    $ecsConfig->ruleWithConfiguration(
        GeneralPhpdocAnnotationRemoveFixer::class,
        [
            'annotations' => ['author', 'package', 'covers', 'category'],
        ],
    );

    // Override configuration from SetList::COMMON, we don't want to remove the inheritdoc annotation
    $ecsConfig->ruleWithConfiguration(
        NoSuperfluousPhpdocTagsFixer::class,
        [
            'remove_inheritdoc' => false,
            'allow_mixed' => true,
        ],
    );

    return [
        // Checked by BrandEmbassyCodingStandard.NamingConvention.CamelCapsFunctionName
        CamelCapsMethodNameSniff::class,
        // Allow empty catch
        'Generic.CodeAnalysis.EmptyStatement.DetectedCatch',
        // Disable that first expression of a multi-line control structure must be on the line after the opening parenthesis -->
        'PSR12.ControlStructures.ControlStructureSpacing.FirstExpressionLine',
        // Disable line indent in a multi-line control structure
        'PSR12.ControlStructures.ControlStructureSpacing.LineIndent',
        // Disable that closing parenthesis of a multi-line control structure must be on the line after the last expression -->
        'PSR12.ControlStructures.ControlStructureSpacing.CloseParenthesisLine',
        // Disable spaces between header blocks (uses for class, const, function)
        'PSR12.ControlStructures.ControlStructureSpacing.SpacingAfterBlock',
        // Allow space between directive and the equals sign in a declare statement
        'PSR12.Files.DeclareStatement.SpaceFoundAfterDirective',
        // Allow space between equal sign and the directive value in a declare statement
        'PSR12.Files.DeclareStatement.SpaceFoundBeforeDirectiveValue',
        // Allow methods to start with underscore
        'PSR2.Methods.MethodDeclaration.Underscore',
        MethodDeclarationSniff::class . '.Underscore',
        // Excluded because it is not compatible 7.4 property type hints + doctrine entities
        VariableCommentSniff::class . '.MissingVar',
        // Allow short versions of scalar types (i.e. int instead of integer)
        VariableCommentSniff::class . '.IncorrectVarType',
        // Checked by SlevomatCodingStandard.TypeHints sniffs
        VariableCommentSniff::class . '.Missing',
        // In conflict with annotationsGroups in SlevomatCodingStandard.Commenting.DocCommentSpacing
        VariableCommentSniff::class . '.VarOrder',
        // Allow multiple values on a single line
        'Squiz.Arrays.ArrayDeclaration.SingleLineNotAllowed',
        // Disable key aligning
        'Squiz.Arrays.ArrayDeclaration.KeyNotAligned.',
        // Disable alignment of braces
        'Squiz.Arrays.ArrayDeclaration.CloseBraceNotAligned',
        // Disable alignment of values with opening brace
        'Squiz.Arrays.ArrayDeclaration.ValueNotAligned',
        // Disable alignment of double arrow
        'Squiz.Arrays.ArrayDeclaration.DoubleArrowNotAligned',
        // Checked by SlevomatCodingStandard.Arrays.TrailingArrayComma.MissingTrailingComma
        'Squiz.Arrays.ArrayDeclaration.NoCommaAfterLast',
        // Disable forcing one line arrays if there is only one value
        'Squiz.Arrays.ArrayDeclaration.MultiLineNotAllowed',
        // Allow extra spaces after star, i.e. for indented annotations
        'Squiz.Commenting.DocCommentAlignment.SpaceAfterStar',
        // Allow inline phpDoc comments
        'PEAR.Commenting.InlineComment.DocBlock',
        // Comments don't have to be sentences
        'PEAR.Commenting.InlineComment.NotCapital',
        // Comments don't have to be sentences
        'PEAR.Commenting.InlineComment.InvalidEndChar',
        // Do not require `@return`
        FunctionCommentSniff::class . '.MissingReturn',
        // Do not require comments for `@param`
        FunctionCommentSniff::class . '.MissingParamComment',
        // Do not require `@param` for all parameters
        FunctionCommentSniff::class . '.MissingParamTag',
        // Breaks when all params are not documented
        FunctionCommentSniff::class . '.ParamNameNoMatch',
        // Does not support collections, i.e. `string[]`
        FunctionCommentSniff::class . '.IncorrectTypeHint',
        // Doesn't respect inheritance
        FunctionCommentSniff::class . '.ScalarTypeHintMissing',
        // Checked by SlevomatCodingStandard.TypeHints sniffs
        FunctionCommentSniff::class . '.TypeHintMissing',
        // Breaks when all params are not documented
        FunctionCommentSniff::class . '.InvalidTypeHint',
        // Does not work properly with PHP 7 / short-named types
        FunctionCommentSniff::class . '.IncorrectParamVarName',
        // Breaks with compound return types, i.e. `string|null`
        FunctionCommentSniff::class . '.InvalidReturnNotVoid',
        // Comments don't have to be sentences
        FunctionCommentSniff::class . '.ParamCommentNotCapital',
        // Comments don't have to be sentences
        FunctionCommentSniff::class . '.ParamCommentFullStop',
        // Doc comment is not required for every method
        FunctionCommentSniff::class . '.Missing',
        // Comment is not required for `@throws` tag
        FunctionCommentSniff::class . '.EmptyThrows',
        // Spacing after param type is not checked
        FunctionCommentSniff::class . '.SpacingAfterParamType',
        // TODO: Discuss on dev sync if we want this one
        FunctionCommentSniff::class . '.ThrowsNoFullStop',
        // TODO: Discuss on dev sync if we want this one
        FunctionCommentSniff::class . '.ThrowsNotCapital',
        // Conflicts with our DeclareStrictTypesSniff configuration which has declare strict on first line
        BlankLineAfterOpeningTagFixer::class,
        // Conflicts with our DeclareStrictTypesSniff configuration
        BlankLineAfterStrictTypesFixer::class,
        // Conflicts with our DeclareStrictTypesSniff configuration
        DeclareStatementSniff::class,
        // Conflicts with our DeclareStrictTypesSniff configuration - it tries to create a blank line after <?php
        FileHeaderSniff::class,
        // Conflicts with FunctionSpacingSniff configuration, which sets 2 newlines between methods
        ClassAttributesSeparationFixer::class,
        // Conflicts with ArrayDeclarationSniff + ArrayIndentationFixer will handle it
        ArrayIndentSniff::class,
        // Conflicts with BinaryOperatorSpacesFixer, ArrayIndentationFixer - let other fixers handle this
        ArrayDeclarationSniff::class,
        // Forbid space after cast // TODO: Remove NoSpaceAfterCastSniff in favor of CastSpacesFixer
        NoSpaceAfterCastSniff::class,
        // TODO: Discuss on dev sync if we want this rule
        NotOperatorWithSuccessorSpaceFixer::class,
        // TODO: Discuss on dev sync if we want this rule
        MethodChainingNewlineFixer::class,
        // TODO: This one changes a lot of code, discuss on dev sync
        LineLengthFixer::class,
        // TODO: ArrayOpenerAndCloserNewlineFixer discuss on dev sync if we want this
        ArrayOpenerAndCloserNewlineFixer::class,
        // TODO: Enable along with ArrayOpenerAndCloserNewlineFixer if agreed upon on dev sync
        ArrayListItemNewlineFixer::class,
        // TODO: Discuss on dev sync, not that setUp and tearDown go to the top (should be configurable)
        OrderedClassElementsFixer::class,
        // TODO: These make no sense really, you need to respect the interface
        UnusedFunctionParameterSniff::class . '.FoundInImplementedInterfaceAfterLastUsed',
        UnusedFunctionParameterSniff::class . '.FoundInImplementedInterfaceBeforeLastUsed',
        UnusedFunctionParameterSniff::class . '.FoundInImplementedInterface',
        // TODO: This makes no sense, as you cannot remove preceding unused parameter for example
        // TODO: when using a callback in \BE\Post\Message\MessageCollection::partition
        UnusedFunctionParameterSniff::class . '.FoundInExtendedClassBeforeLastUsed',
        // TODO: This makes no sense, as you want to respect parent class method signature
        // TODO: This rule makes sense only if you want to adjust parent class method signature based on usages
        UnusedFunctionParameterSniff::class . '.FoundInExtendedClassAfterLastUsed',
        // TODO: This rule makes sense only if you want to adjust parent class method signature based on usages
        UnusedFunctionParameterSniff::class . '.FoundInExtendedClass',
        // TODO: This rule makes sense only if you want to adjust parent class method signature based on usages
        UnusedFunctionParameterSniff::class . '.FoundBeforeLastUsed',
        // TODO: Unsafe, needs manual verification
        PropertyTypeHintSniff::class . '.MissingNativeTypeHint',
        ParameterTypeHintSniff::class . '.MissingNativeTypeHint',
        // TODO: Unsafe, needs manual verification + I was unable to selectively suppress what is being reported as
        // TODO: SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff.SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
        // TODO: which can actually break code by adding return type hints which are missing in child classes
        ReturnTypeHintSniff::class,
        // Does not work right with switch statements
        ScopeIndentSniff::class . '.IncorrectExact',
        // Does not work correctly with complex doctrine annotations
        DocCommentAlignmentSniff::class . '.SpaceAfterStar',
        // Handled by StrictComparisonFixer
        DisallowEqualOperatorsSniff::class . '.DisallowedEqualOperator',
        DisallowEqualOperatorsSniff::class . '.DisallowedNotEqualOperator',
    ];

    // endregion === brand embassy coding standard ===
};
