<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\Annotation\Annotation;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\DocCommentHelper;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\ParameterTypeHint;
use SlevomatCodingStandard\Helpers\ReturnTypeHint;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\SuppressHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\TypeHintHelper;
use function array_flip;
use function array_key_exists;
use function array_map;
use function count;
use function explode;
use function in_array;
use function preg_match;
use function preg_split;
use function sprintf;
use function stripos;
use function strpos;
use function strtolower;
use function substr;
use const PHP_VERSION_ID;
use const T_DOC_COMMENT_CLOSE_TAG;
use const T_DOC_COMMENT_STAR;
use const T_DOC_COMMENT_STRING;
use const T_DOC_COMMENT_TAG;
use const T_DOC_COMMENT_WHITESPACE;
use const T_FUNCTION;

class TypeHintDeclarationSniff implements Sniff
{
    private const NAME = 'BrandEmbassyCodingStandard.TypeHints.TypeHintDeclaration';
    public const CODE_MISSING_PARAMETER_TYPE_HINT = 'MissingParameterTypeHint';
    public const CODE_MISSING_PROPERTY_TYPE_HINT = 'MissingPropertyTypeHint';
    public const CODE_MISSING_RETURN_TYPE_HINT = 'MissingReturnTypeHint';
    public const CODE_MISSING_TRAVERSABLE_PARAMETER_TYPE_HINT_SPECIFICATION = 'MissingTraversableParameterTypeHintSpecification';
    public const CODE_MISSING_TRAVERSABLE_PROPERTY_TYPE_HINT_SPECIFICATION = 'MissingTraversablePropertyTypeHintSpecification';
    public const CODE_MISSING_TRAVERSABLE_RETURN_TYPE_HINT_SPECIFICATION = 'MissingTraversableReturnTypeHintSpecification';
    public const CODE_USELESS_PARAMETER_ANNOTATION = 'UselessParameterAnnotation';
    public const CODE_USELESS_RETURN_ANNOTATION = 'UselessReturnAnnotation';
    public const CODE_INCORRECT_RETURN_TYPE_HINT = 'IncorrectReturnTypeHint';
    public const CODE_USELESS_DOC_COMMENT = 'UselessDocComment';

    /**
     * @var bool
     *
     * @deprecated
     */
    public $enableNullableTypeHints = true;

    /**
     * @var bool
     *
     * @deprecated
     */
    public $enableVoidTypeHint = true;

    /** @var bool */
    public $enableObjectTypeHint = PHP_VERSION_ID >= 70200;

    /** @var bool */
    public $enableEachParameterAndReturnInspection = false;

    /** @var string[] */
    public $traversableTypeHints = [];

    /** @var string[] */
    public $usefulAnnotations = [];

    /** @var bool */
    public $allAnnotationsAreUseful = false;

    /** @var int[]|null [string => int] */
    private $normalizedTraversableTypeHints;

    /** @var string[]|null */
    private $normalizedUsefulAnnotations;


    /**
     * @phpcsSuppress BrandEmbassyCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @param File $phpcsFile
     * @param int  $pointer
     */
    public function process(File $phpcsFile, $pointer): void
    {
        $this->checkUselessDocComment($phpcsFile, $pointer);
    }


    /**
     * @return mixed[]
     */
    public function register(): array
    {
        return [T_FUNCTION];
    }


    private function checkUselessDocComment(File $phpcsFile, int $functionPointer): void
    {
        $docCommentSniffSuppressed = SuppressHelper::isSniffSuppressed(
            $phpcsFile,
            $functionPointer,
            $this->getSniffName(self::CODE_USELESS_DOC_COMMENT)
        );
        $returnSniffSuppressed = SuppressHelper::isSniffSuppressed(
            $phpcsFile,
            $functionPointer,
            $this->getSniffName(self::CODE_USELESS_RETURN_ANNOTATION)
        );
        $parameterSniffSuppressed = SuppressHelper::isSniffSuppressed(
            $phpcsFile,
            $functionPointer,
            $this->getSniffName(self::CODE_USELESS_PARAMETER_ANNOTATION)
        );

        if ($docCommentSniffSuppressed && $returnSniffSuppressed && $parameterSniffSuppressed) {
            return;
        }

        if ($this->hasInheritdocAnnotation($phpcsFile, $functionPointer)) {
            return;
        }

        if (!DocCommentHelper::hasDocComment($phpcsFile, $functionPointer)) {
            return;
        }

        $containsUsefulInformation = DocCommentHelper::hasDocCommentDescription($phpcsFile, $functionPointer);

        $parametersNames = FunctionHelper::getParametersNames($phpcsFile, $functionPointer);
        $parametersContainDescription = [];
        foreach (FunctionHelper::getParametersAnnotations(
            $phpcsFile,
            $functionPointer
        ) as $parameterAnnotationNo => $parameterAnnotation) {
            if ($parameterAnnotation->getContent() === null) {
                continue;
            }

            if (!preg_match(
                '~^\\S+\\s++(?:(?:\.{3}\\s*)?(\$\\S+)\\s+)?[^$]~',
                $parameterAnnotation->getContent(),
                $matches
            )) {
                continue;
            }

            if (isset($matches[1])) {
                $parametersContainDescription[$matches[1]] = true;
                $containsUsefulInformation = true;
            } elseif (isset($parametersNames[$parameterAnnotationNo])) {
                $parametersContainDescription[$parametersNames[$parameterAnnotationNo]] = true;
                $containsUsefulInformation = true;
            }
        }

        $returnTypeHint = FunctionHelper::findReturnTypeHint($phpcsFile, $functionPointer);
        $returnAnnotation = FunctionHelper::findReturnAnnotation($phpcsFile, $functionPointer);
        $isReturnAnnotationUseless = $this->isReturnAnnotationUseless(
            $phpcsFile,
            $functionPointer,
            $returnTypeHint,
            $returnAnnotation
        );

        $parameterTypeHints = FunctionHelper::getParametersTypeHints($phpcsFile, $functionPointer);
        $parametersAnnotationTypeHints = $this->getFunctionParameterTypeHintsDefinitions($phpcsFile, $functionPointer);
        $uselessParameterAnnotations = $this->getUselessParameterAnnotations(
            $phpcsFile,
            $functionPointer,
            $parameterTypeHints,
            $parametersAnnotationTypeHints,
            $parametersContainDescription
        );

        foreach (AnnotationHelper::getAnnotations($phpcsFile, $functionPointer) as [$annotation]) {
            if ($annotation->getName() === SuppressHelper::ANNOTATION) {
                $containsUsefulInformation = true;
                break;
            }

            if ($this->allAnnotationsAreUseful && !in_array($annotation->getName(), ['@param', '@return'], true)) {
                $containsUsefulInformation = true;
                break;
            }

            foreach ($this->getNormalizedUsefulAnnotations() as $usefulAnnotation) {
                if ($annotation->getName() === $usefulAnnotation) {
                    $containsUsefulInformation = true;
                    break;
                }

                if (substr($usefulAnnotation, -1) !== '\\' || strpos($annotation->getName(), $usefulAnnotation) !== 0) {
                    continue;
                }

                $containsUsefulInformation = true;
                break;
            }
        }

        $isWholeDocCommentUseless = !$containsUsefulInformation
            && ($returnAnnotation === null || $isReturnAnnotationUseless)
            && count($uselessParameterAnnotations) === count($parametersAnnotationTypeHints);

        if ($this->enableEachParameterAndReturnInspection && (!$isWholeDocCommentUseless || $docCommentSniffSuppressed)) {
            if ($returnAnnotation !== null && $isReturnAnnotationUseless && !$returnSniffSuppressed) {
                $fix = $phpcsFile->addFixableError(
                    sprintf(
                        '%s %s() has useless @return annotation.',
                        $this->getFunctionTypeLabel($phpcsFile, $functionPointer),
                        FunctionHelper::getFullyQualifiedName($phpcsFile, $functionPointer)
                    ),
                    $returnAnnotation->getStartPointer(),
                    self::CODE_USELESS_RETURN_ANNOTATION
                );
                if ($fix) {
                    $tokens = $phpcsFile->getTokens();
                    $docCommentOpenPointer = DocCommentHelper::findDocCommentOpenToken($phpcsFile, $functionPointer);
                    $docCommentClosePointer = $tokens[$docCommentOpenPointer]['comment_closer'];

                    for ($i = $docCommentOpenPointer + 1; $i < $docCommentClosePointer; $i++) {
                        if ($tokens[$i]['code'] !== T_DOC_COMMENT_TAG) {
                            continue;
                        }

                        if ($tokens[$i]['content'] !== '@return') {
                            continue;
                        }

                        /** @var int $changeStart */
                        $changeStart = TokenHelper::findPrevious(
                            $phpcsFile,
                            [T_DOC_COMMENT_STAR],
                            $i - 1,
                            $docCommentOpenPointer
                        );
                        /** @var int $changeEnd */
                        $changeEnd = TokenHelper::findNext(
                            $phpcsFile,
                            [T_DOC_COMMENT_CLOSE_TAG, T_DOC_COMMENT_STAR],
                            $i - 1,
                            $docCommentClosePointer + 1
                        ) - 1;
                        $phpcsFile->fixer->beginChangeset();
                        for ($j = $changeStart; $j <= $changeEnd; $j++) {
                            $phpcsFile->fixer->replaceToken($j, '');
                        }
                        $phpcsFile->fixer->endChangeset();

                        break;
                    }
                }
            }

            if (!$parameterSniffSuppressed) {
                $parameterNamesWithUselessAnnotation = array_map(
                    function (array $uselessParameterAnnotation): string {
                        return $uselessParameterAnnotation['parameterName'];
                    },
                    $uselessParameterAnnotations
                );

                foreach ($uselessParameterAnnotations as $uselessParameterAnnotation) {
                    $fix = $phpcsFile->addFixableError(
                        sprintf(
                            '%s %s() has useless @param annotation for parameter %s.',
                            $this->getFunctionTypeLabel($phpcsFile, $functionPointer),
                            FunctionHelper::getFullyQualifiedName($phpcsFile, $functionPointer),
                            $uselessParameterAnnotation['parameterName']
                        ),
                        $uselessParameterAnnotation['pointer'],
                        self::CODE_USELESS_PARAMETER_ANNOTATION
                    );
                    if (!$fix) {
                        continue;
                    }

                    $tokens = $phpcsFile->getTokens();
                    $docCommentOpenPointer = DocCommentHelper::findDocCommentOpenToken($phpcsFile, $functionPointer);
                    $docCommentClosePointer = $tokens[$docCommentOpenPointer]['comment_closer'];

                    for ($i = $docCommentOpenPointer + 1; $i < $docCommentClosePointer; $i++) {
                        if ($tokens[$i]['code'] !== T_DOC_COMMENT_TAG) {
                            continue;
                        }

                        if ($tokens[$i]['content'] !== '@param') {
                            continue;
                        }

                        $parameterInformationPointer = TokenHelper::findNextExcluding(
                            $phpcsFile,
                            [T_DOC_COMMENT_WHITESPACE],
                            $i + 1,
                            $docCommentClosePointer + 1
                        );

                        if ($parameterInformationPointer === null || $tokens[$parameterInformationPointer]['code'] !== T_DOC_COMMENT_STRING) {
                            continue;
                        }

                        if (!preg_match('~\S+\s+(\$\S+)~', $tokens[$parameterInformationPointer]['content'], $match)) {
                            continue;
                        }

                        if (!in_array($match[1], $parameterNamesWithUselessAnnotation, true)) {
                            continue;
                        }

                        /** @var int $changeStart */
                        $changeStart = TokenHelper::findPrevious($phpcsFile, [T_DOC_COMMENT_STAR], $i - 1);
                        /** @var int $changeEnd */
                        $changeEnd = TokenHelper::findNext(
                            $phpcsFile,
                            [T_DOC_COMMENT_CLOSE_TAG, T_DOC_COMMENT_STAR],
                            $i - 1
                        ) - 1;
                        $phpcsFile->fixer->beginChangeset();
                        for ($j = $changeStart; $j <= $changeEnd; $j++) {
                            $phpcsFile->fixer->replaceToken($j, '');
                        }
                        $phpcsFile->fixer->endChangeset();

                        break;
                    }
                }
            }

            return;
        }

        if (!$isWholeDocCommentUseless || $docCommentSniffSuppressed) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            sprintf(
                '%s %s() does not need documentation comment.',
                $this->getFunctionTypeLabel($phpcsFile, $functionPointer),
                FunctionHelper::getFullyQualifiedName($phpcsFile, $functionPointer)
            ),
            $functionPointer,
            self::CODE_USELESS_DOC_COMMENT
        );
        if (!$fix) {
            return;
        }

        /** @var int $docCommentOpenPointer */
        $docCommentOpenPointer = DocCommentHelper::findDocCommentOpenToken($phpcsFile, $functionPointer);
        $docCommentClosePointer = $phpcsFile->getTokens()[$docCommentOpenPointer]['comment_closer'];

        $changeStart = $docCommentOpenPointer;
        /** @var int $changeEnd */
        $changeEnd = TokenHelper::findNextEffective($phpcsFile, $docCommentClosePointer + 1) - 1;

        $phpcsFile->fixer->beginChangeset();
        for ($i = $changeStart; $i <= $changeEnd; $i++) {
            $phpcsFile->fixer->replaceToken($i, '');
        }
        $phpcsFile->fixer->endChangeset();
    }


    private function getSniffName(string $sniffName): string
    {
        return sprintf('%s.%s', self::NAME, $sniffName);
    }


    private function definitionContainsNullTypeHint(string $typeHintDefinition): bool
    {
        return preg_match('~(?:^null$)|(?:^null\|)|(?:\|null\|)|(?:\|null$)~i', $typeHintDefinition) !== 0;
    }


    private function definitionContainsStaticOrThisTypeHint(string $typeHintDefinition): bool
    {
        return preg_match('~(?:^static$)|(?:^static\|)|(?:\|static\|)|(?:\|static$)~i', $typeHintDefinition) !== 0
            || preg_match('~(?:^\$this$)|(?:^\$this\|)|(?:\|\$this\|)|(?:\|\$this$)~i', $typeHintDefinition) !== 0;
    }


    private function definitionContainsOneTypeHint(string $typeHintDefinition): bool
    {
        return strpos($typeHintDefinition, '|') === false;
    }


    private function definitionContainsJustTwoTypeHints(string $typeHintDefinition): bool
    {
        return count(explode('|', $typeHintDefinition)) === 2;
    }


    private function isTraversableTypeHint(string $typeHint): bool
    {
        return TypeHintHelper::isSimpleIterableTypeHint($typeHint) || array_key_exists(
            $typeHint,
            $this->getNormalizedTraversableTypeHints()
        );
    }


    /**
     * @return int[] [string => int]
     */
    private function getNormalizedTraversableTypeHints(): array
    {
        if ($this->normalizedTraversableTypeHints === null) {
            $this->normalizedTraversableTypeHints = array_flip(
                array_map(
                    function (string $typeHint): string {
                        return NamespaceHelper::isFullyQualifiedName($typeHint) ? $typeHint : sprintf(
                            '%s%s',
                            NamespaceHelper::NAMESPACE_SEPARATOR,
                            $typeHint
                        );
                    },
                    SniffSettingsHelper::normalizeArray($this->traversableTypeHints)
                )
            );
        }

        return $this->normalizedTraversableTypeHints;
    }


    /**
     * @return string[]
     */
    private function getNormalizedUsefulAnnotations(): array
    {
        if ($this->normalizedUsefulAnnotations === null) {
            $this->normalizedUsefulAnnotations = SniffSettingsHelper::normalizeArray($this->usefulAnnotations);
        }

        return $this->normalizedUsefulAnnotations;
    }


    private function getFunctionTypeLabel(File $phpcsFile, int $functionPointer): string
    {
        return FunctionHelper::isMethod($phpcsFile, $functionPointer) ? 'Method' : 'Function';
    }


    /**
     * @param File $phpcsFile
     * @param int  $functionPointer
     *
     * @return mixed[][] [string => [int, string]]
     */
    private function getFunctionParameterTypeHintsDefinitions(File $phpcsFile, int $functionPointer): array
    {
        $parametersNames = FunctionHelper::getParametersNames($phpcsFile, $functionPointer);
        $parametersTypeHintsDefinitions = [];
        foreach (FunctionHelper::getParametersAnnotations(
            $phpcsFile,
            $functionPointer
        ) as $parameterAnnotationNo => $parameterAnnotation) {
            if ($parameterAnnotation->getContent() === null) {
                continue;
            }

            if (!preg_match(
                '~^([^$]\\S*)(?:\\s+(?:\.{3}\\s*)?(\$\\S+))?~',
                $parameterAnnotation->getContent(),
                $matches
            )) {
                continue;
            }

            if (isset($matches[2])) {
                $parametersTypeHintsDefinitions[$matches[2]] = [
                    'pointer'    => $parameterAnnotation->getStartPointer(),
                    'definition' => $matches[1],
                ];
            } elseif (isset($parametersNames[$parameterAnnotationNo])) {
                $parametersTypeHintsDefinitions[$parametersNames[$parameterAnnotationNo]] = [
                    'pointer'    => $parameterAnnotation->getStartPointer(),
                    'definition' => $matches[1],
                ];
            }
        }

        return $parametersTypeHintsDefinitions;
    }


    private function typeHintEqualsAnnotation(
        File $phpcsFile,
        int $functionPointer,
        string $typeHint,
        string $typeHintInAnnotation
    ): bool {
        return TypeHintHelper::getFullyQualifiedTypeHint(
            $phpcsFile,
            $functionPointer,
            $typeHint
        ) === TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $typeHintInAnnotation);
    }


    private function isReturnAnnotationUseless(
        File $phpcsFile,
        int $functionPointer,
        ?ReturnTypeHint $returnTypeHint = null,
        ?Annotation $returnAnnotation = null
    ): bool {
        if ($returnTypeHint === null || $returnAnnotation === null || $returnAnnotation->getContent() === null) {
            return false;
        }

        if (preg_match('~^\\S+\\s+\\S+~', $returnAnnotation->getContent())) {
            return false;
        }

        $returnTypeHintDefinition = preg_split('~\\s+~', $returnAnnotation->getContent())[0];

        if ($this->isTraversableTypeHint(
            TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $returnTypeHint->getTypeHint())
        )) {
            return false;
        }

        if ($this->definitionContainsStaticOrThisTypeHint($returnTypeHintDefinition)) {
            return false;
        }

        if ($this->enableNullableTypeHints && $this->isTypeHintDefinitionCompoundOfNull($returnTypeHintDefinition)) {
            return $this->typeHintEqualsAnnotation(
                $phpcsFile,
                $functionPointer,
                $returnTypeHint->getTypeHint(),
                $this->getTypeFromNullableTypeHintDefinition($returnTypeHintDefinition)
            );
        }

        if (!$this->definitionContainsOneTypeHint($returnTypeHintDefinition)) {
            return false;
        }

        if (!$this->typeHintEqualsAnnotation(
            $phpcsFile,
            $functionPointer,
            $returnTypeHint->getTypeHint(),
            $returnTypeHintDefinition
        )) {
            return false;
        }

        return true;
    }


    private function isTypeHintDefinitionCompoundOfNull(string $definition): bool
    {
        return $this->definitionContainsJustTwoTypeHints($definition) && $this->definitionContainsNullTypeHint(
            $definition
        );
    }


    private function getTypeFromNullableTypeHintDefinition(string $definition): string
    {
        $defitionParts = explode('|', $definition);

        return strtolower($defitionParts[0]) === 'null' ? $defitionParts[1] : $defitionParts[0];
    }


    /**
     * @param File                       $phpcsFile
     * @param int                        $functionPointer
     * @param ParameterTypeHint[]|null[] $functionTypeHints
     * @param mixed[][]                  $parametersTypeHintsDefinitions
     * @param bool[]                     $parametersContainDescription
     *
     * @return mixed[][]
     */
    private function getUselessParameterAnnotations(
        File $phpcsFile,
        int $functionPointer,
        array $functionTypeHints,
        array $parametersTypeHintsDefinitions,
        array $parametersContainDescription
    ): array {
        $uselessParameterNames = [];

        foreach ($functionTypeHints as $parameterName => $parameterTypeHint) {
            if ($parameterTypeHint === null) {
                continue;
            }

            if (!array_key_exists($parameterName, $parametersTypeHintsDefinitions)) {
                continue;
            }

            if (array_key_exists($parameterName, $parametersContainDescription)) {
                continue;
            }

            if ($this->isTraversableTypeHint(
                TypeHintHelper::getFullyQualifiedTypeHint(
                    $phpcsFile,
                    $functionPointer,
                    $parameterTypeHint->getTypeHint()
                )
            )) {
                continue;
            }

            /** @var string $parameterTypeHintDefinition */
            $parameterTypeHintDefinition = $parametersTypeHintsDefinitions[$parameterName]['definition'];
            if ($this->definitionContainsStaticOrThisTypeHint($parameterTypeHintDefinition)) {
                continue;
            } elseif ($this->isTypeHintDefinitionCompoundOfNull($parameterTypeHintDefinition)) {
                if (!$this->typeHintEqualsAnnotation(
                    $phpcsFile,
                    $functionPointer,
                    $parameterTypeHint->getTypeHint(),
                    $this->getTypeFromNullableTypeHintDefinition($parameterTypeHintDefinition)
                )) {
                    continue;
                }
            } elseif (!$this->definitionContainsOneTypeHint($parameterTypeHintDefinition)) {
                continue;
            } elseif (!$this->typeHintEqualsAnnotation(
                $phpcsFile,
                $functionPointer,
                $parameterTypeHint->getTypeHint(),
                $parameterTypeHintDefinition
            )) {
                continue;
            }

            $uselessParameterNames[] = [
                'pointer'       => $parametersTypeHintsDefinitions[$parameterName]['pointer'],
                'parameterName' => $parameterName,
            ];
        }

        return $uselessParameterNames;
    }


    private function hasInheritdocAnnotation(File $phpcsFile, int $pointer): bool
    {
        $docComment = DocCommentHelper::getDocComment($phpcsFile, $pointer);
        if ($docComment === null) {
            return false;
        }

        return stripos($docComment, '@inheritdoc') !== false;
    }
}
