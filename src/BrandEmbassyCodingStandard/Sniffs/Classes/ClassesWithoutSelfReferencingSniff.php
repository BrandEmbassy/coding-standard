<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\StringHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_filter;
use function array_map;
use function assert;
use function in_array;
use function is_a;
use function sprintf;
use const T_CLASS;
use const T_DOUBLE_COLON;
use const T_SELF;
use const T_STATIC;
use const T_STRING;

/**
 * @final
 */
class ClassesWithoutSelfReferencingSniff implements Sniff
{
    private const VIOLATION_CODE = 'ClassesWithoutSelfReferencing';

    /**
     * @var array<string, class-string>
     */
    public $classesWithoutSelfReferencing = [];

    /**
     * @var array<class-string, string[]>
     */
    private static $classStaticMethods = [];


    /**
     * @return int[]|string[]
     */
    public function register(): array
    {
        return [T_DOUBLE_COLON];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     *
     * @param int $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $classPointer = TokenHelper::findPrevious($phpcsFile, [T_CLASS], $stackPtr);

        if ($classPointer === null) {
            return;
        }

        $className = ClassHelper::getFullyQualifiedName($phpcsFile, $classPointer);

        $classesWithoutSelfReferencing = $this->findClassesWithoutSelfReferencing($className);

        foreach ($classesWithoutSelfReferencing as $classWithoutSelfReferencing) {
            $this->checkClassHasNoSelfReferences($phpcsFile, $stackPtr, $classWithoutSelfReferencing);
        }
    }


    /**
     * @return class-string[]
     */
    private function findClassesWithoutSelfReferencing(string $className): array
    {
        return array_filter(
            $this->classesWithoutSelfReferencing,
            static function (string $forbiddenClass) use ($className): bool {
                return is_a($className, $forbiddenClass, true);
            }
        );
    }


    /**
     * @param class-string $className
     */
    private function checkClassHasNoSelfReferences(File $phpcsFile, int $doubleColonPointer, string $className): void
    {
        $staticMethodsToCheck = $this->getClassStaticMethods($className);

        $selfReferencingStaticMethodCallPointers = $this->findSelfReferencingStaticMethodCallPointers(
            $phpcsFile,
            $doubleColonPointer,
            $staticMethodsToCheck
        );

        if ($selfReferencingStaticMethodCallPointers !== null) {
            $this->addMethodCallError($phpcsFile, $selfReferencingStaticMethodCallPointers, $className);
        }
    }


    /**
     * @param class-string $className
     *
     * @return string[]
     */
    private function getClassStaticMethods(string $className): array
    {
        if (isset(self::$classStaticMethods[$className])) {
            return self::$classStaticMethods[$className];
        }

        $reflection = new ReflectionClass($className);
        $classStaticMethods = array_map(
            static function (ReflectionMethod $method): string {
                return $method->name;
            },
            $reflection->getMethods(ReflectionMethod::IS_STATIC)
        );

        self::$classStaticMethods[$className] = $classStaticMethods;

        return $classStaticMethods;
    }


    /**
     * @param string[] $staticMethodsToCheck
     *
     * @return array<string, int>|null
     */
    private function findSelfReferencingStaticMethodCallPointers(
        File $phpcsFile,
        int $doubleColonPointer,
        array $staticMethodsToCheck
    ): ?array {
        $tokens = $phpcsFile->getTokens();

        $previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $doubleColonPointer - 1);
        assert($previousPointer !== null);

        if (!in_array($tokens[$previousPointer]['code'], [T_STATIC, T_SELF], true)) {
            return null;
        }

        $nextPointer = TokenHelper::findNextEffective($phpcsFile, $doubleColonPointer + 1);
        assert($nextPointer !== null);

        if ($tokens[$nextPointer]['code'] !== T_STRING) {
            return null;
        }

        if (!in_array($tokens[$nextPointer]['content'], $staticMethodsToCheck, true)) {
            return null;
        }

        return ['selfReferencePointer' => $previousPointer, 'methodNamePointer' => $nextPointer];
    }


    /**
     * @param array<string, int> $selfReferencingStaticMethodCallPointers
     */
    private function addMethodCallError(
        File $phpcsFile,
        array $selfReferencingStaticMethodCallPointers,
        string $classWithoutSelfReferencing
    ): void {
        $selfReferencePointer = $selfReferencingStaticMethodCallPointers['selfReferencePointer'];
        $methodNamePointer = $selfReferencingStaticMethodCallPointers['methodNamePointer'];

        $selfReference = TokenHelper::getContent($phpcsFile, $selfReferencePointer, $selfReferencePointer);
        $methodName = TokenHelper::getContent($phpcsFile, $methodNamePointer, $methodNamePointer);

        $errorMessage = sprintf(
            'Using %1$s::%2$s is forbidden. Call %3$s::%2$s directly.',
            $selfReference,
            $methodName,
            $classWithoutSelfReferencing
        );
        $fix = $phpcsFile->addFixableError($errorMessage, $selfReferencePointer, self::VIOLATION_CODE);

        if (!$fix) {
            return;
        }

        if (!StringHelper::startsWith($classWithoutSelfReferencing, '\\')) {
            $classWithoutSelfReferencing = '\\' . $classWithoutSelfReferencing;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->replaceToken($selfReferencePointer, $classWithoutSelfReferencing);
        $phpcsFile->fixer->endChangeset();
    }
}
