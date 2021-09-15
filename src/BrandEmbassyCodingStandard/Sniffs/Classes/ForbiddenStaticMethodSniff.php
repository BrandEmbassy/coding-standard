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
use function in_array;
use function is_a;
use function sprintf;
use const T_CLASS;
use const T_DOUBLE_COLON;
use const T_SELF;
use const T_STATIC;
use const T_STRING;

final class ForbiddenStaticMethodSniff implements Sniff
{
    private const FORBIDDEN_SELF_METHOD_CALL = 'ForbiddenSelfMethodCall';

    /**
     * @var array<string, class-string>
     */
    public $forbiddenClasses = [];

    /**
     * @var array<class-string, string[]>
     */
    private static $forbiddenMethods = [];


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

        $methodCallPointer = $this->findForbiddenStaticMethodPointer(
            $phpcsFile,
            $doubleColonPointer,
            $forbiddenClassStaticMethods
        );

        if ($methodCallPointer !== null) {
            $this->addMethodCallError($phpcsFile, $methodCallPointer, $forbiddenClass);
        }
    }


    private function addMethodCallError(
        File $phpcsFile,
        int $staticMethodCallPointer,
        string $forbiddenClass
    ): void {
        $call = TokenHelper::getContent($phpcsFile, $staticMethodCallPointer, $staticMethodCallPointer);

        $errorMessage = sprintf('Using %s:: is forbidden. Call %s:: directly', $call, $forbiddenClass);
        $fix = $phpcsFile->addFixableError(
            $errorMessage,
            $staticMethodCallPointer,
            self::FORBIDDEN_SELF_METHOD_CALL
        );

        if (!$fix) {
            return;
        }

        if (!StringHelper::startsWith($forbiddenClass, '\\')) {
            $forbiddenClass = '\\' . $forbiddenClass;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->replaceToken($staticMethodCallPointer, $forbiddenClass);
        $phpcsFile->fixer->endChangeset();
    }


    /**
     * @param string[] $forbiddenMethods
     */
    private function findForbiddenStaticMethodPointer(
        File $phpcsFile,
        int $doubleColonPointer,
        array $forbiddenMethods
    ): ?int {
        $tokens = $phpcsFile->getTokens();

        $previousPointer = TokenHelper::findPreviousExcluding(
            $phpcsFile,
            TokenHelper::$ineffectiveTokenCodes,
            $doubleColonPointer - 1
        );

        if (!in_array($tokens[$previousPointer]['code'], [T_STATIC, T_SELF], true)) {
            return null;
        }

        $methodCallPointer = TokenHelper::findNextExcluding(
            $phpcsFile,
            TokenHelper::$ineffectiveTokenCodes,
            $doubleColonPointer + 1
        );

        $methodCallToken = $tokens[$methodCallPointer];

        if (!isset($tokens[$methodCallPointer]) || $methodCallToken['code'] !== T_STRING) {
            return null;
        }

        if (!in_array($methodCallToken['content'], $forbiddenMethods, true)) {
            return null;
        }

        return $previousPointer;
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
        if (isset(self::$forbiddenMethods[$forbiddenClass])) {
            return self::$forbiddenMethods[$forbiddenClass];
        }

        $reflection = new ReflectionClass($forbiddenClass);
        $forbiddenClassStaticMethods = array_map(
            static function (ReflectionMethod $method): string {
                return $method->name;
            },
            $reflection->getMethods(ReflectionMethod::IS_STATIC)
        );

        self::$forbiddenMethods[$forbiddenClass] = $forbiddenClassStaticMethods;

        return $forbiddenClassStaticMethods;
    }
}
