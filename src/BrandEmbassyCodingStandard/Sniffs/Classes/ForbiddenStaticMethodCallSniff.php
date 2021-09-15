<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use ReflectionClass;
use ReflectionMethod;
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

final class ForbiddenStaticMethodCallSniff implements Sniff
{
    private const FORBIDDEN_SELF_METHOD_CALL = 'ForbiddenStaticMethodCall';

    /**
     * @var array<string, class-string>
     */
    public $forbiddenClasses = [];

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

        $forbiddenClasses = $this->findForbiddenClasses($className);

        foreach ($forbiddenClasses as $forbiddenClass) {
            $this->checkForbiddenClass($phpcsFile, $stackPtr, $forbiddenClass);
        }
    }


    /**
     * @param class-string $forbiddenClass
     */
    private function checkForbiddenClass(File $phpcsFile, int $doubleColonPointer, string $forbiddenClass): void
    {
        $forbiddenClassStaticMethods = $this->getStaticMethodsInForbiddenClass($forbiddenClass);

        $staticMethodCallPointers = $this->findStaticMethodCallPointers(
            $phpcsFile,
            $doubleColonPointer,
            $forbiddenClassStaticMethods
        );

        if ($staticMethodCallPointers !== null) {
            $this->addMethodCallError($phpcsFile, $staticMethodCallPointers, $forbiddenClass);
        }
    }


    /**
     * @param array<string, int> $staticMethodCallPointer
     */
    private function addMethodCallError(
        File $phpcsFile,
        array $staticMethodCallPointer,
        string $forbiddenClass
    ): void {
        $callTypePointer = $staticMethodCallPointer['previousPointer'];
        $methodNamePointer = $staticMethodCallPointer['methodNamePointer'];

        $callType = TokenHelper::getContent($phpcsFile, $callTypePointer, $callTypePointer);
        $methodName = TokenHelper::getContent($phpcsFile, $methodNamePointer, $methodNamePointer);

        $errorMessage = sprintf(
            'Using %s::%s is forbidden. Call %s::%s directly.',
            $callType,
            $methodName,
            $forbiddenClass,
            $methodName
        );
        $fix = $phpcsFile->addFixableError(
            $errorMessage,
            $callTypePointer,
            self::FORBIDDEN_SELF_METHOD_CALL
        );

        if (!$fix) {
            return;
        }

        if (!StringHelper::startsWith($forbiddenClass, '\\')) {
            $forbiddenClass = '\\' . $forbiddenClass;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->replaceToken($callTypePointer, $forbiddenClass);
        $phpcsFile->fixer->endChangeset();
    }


    /**
     * @param string[] $staticMethods
     *
     * @return array<string, int>|null
     */
    private function findStaticMethodCallPointers(
        File $phpcsFile,
        int $doubleColonPointer,
        array $staticMethods
    ): ?array {
        $tokens = $phpcsFile->getTokens();

        $previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $doubleColonPointer - 1);
        assert($previousPointer !== null);

        if (!in_array($tokens[$previousPointer]['code'], [T_STATIC, T_SELF], true)) {
            return null;
        }

        $nextPointer = TokenHelper::findNextEffective(
            $phpcsFile,
            $doubleColonPointer + 1
        );
        assert($nextPointer !== null);

        $nextToken = $tokens[$nextPointer];

        if (!isset($tokens[$nextPointer]) || $nextToken['code'] !== T_STRING) {
            return null;
        }

        if (!in_array($nextToken['content'], $staticMethods, true)) {
            return null;
        }

        return ['previousPointer' => $previousPointer, 'methodNamePointer' => $nextPointer];
    }


    /**
     * @return class-string[]
     */
    private function findForbiddenClasses(string $className): array
    {
        return array_filter(
            $this->forbiddenClasses,
            static function (string $forbiddenClass) use ($className): bool {
                return is_a($className, $forbiddenClass, true);
            }
        );
    }


    /**
     * @param class-string $forbiddenClass
     *
     * @return string[]
     */
    private function getStaticMethodsInForbiddenClass(string $forbiddenClass): array
    {
        if (isset(self::$classStaticMethods[$forbiddenClass])) {
            return self::$classStaticMethods[$forbiddenClass];
        }

        $reflection = new ReflectionClass($forbiddenClass);
        $forbiddenClassStaticMethods = array_map(
            static function (ReflectionMethod $method): string {
                return $method->name;
            },
            $reflection->getMethods(ReflectionMethod::IS_STATIC)
        );

        self::$classStaticMethods[$forbiddenClass] = $forbiddenClassStaticMethods;

        return $forbiddenClassStaticMethods;
    }
}
