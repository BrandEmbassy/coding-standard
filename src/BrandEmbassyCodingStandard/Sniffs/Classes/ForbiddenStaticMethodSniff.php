<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use ReflectionClass;
use ReflectionMethod;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\StringHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
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
     * @return int[]|string[]
     */
    public function register(): array
    {
        return [
            T_SELF,
            T_STATIC,
        ];
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

        $forbiddenClass = $this->findForbiddenClass($className);

        if ($forbiddenClass === null) {
            return;
        }

        $this->checkForbiddenClass($phpcsFile, $stackPtr, $forbiddenClass);
    }


    /**
     * @param class-string $forbiddenClass
     */
    private function checkForbiddenClass(File $phpcsFile, int $staticCallPointer, string $forbiddenClass): void
    {
        $reflection = new ReflectionClass($forbiddenClass);
        $forbiddenClassStaticMethods = array_map(
            static function (ReflectionMethod $method): string {
                return $method->name;
            },
            $reflection->getMethods(ReflectionMethod::IS_STATIC)
        );

        if ($this->isForbiddenStaticMethodCall($phpcsFile, $staticCallPointer, $forbiddenClassStaticMethods)) {
            $this->addMethodCallError($phpcsFile, $staticCallPointer, $forbiddenClass);
        }
    }


    private function addMethodCallError(
        File $phpcsFile,
        int $staticMethodCallPointer,
        string $forbiddenClass
    ): void {
        $methodNamePointer = $staticMethodCallPointer + 2;
        $call = TokenHelper::getContent($phpcsFile, $staticMethodCallPointer, $methodNamePointer);
        $expectedCall = TokenHelper::getContent($phpcsFile, $staticMethodCallPointer + 1, $methodNamePointer);

        $errorMessage = sprintf('Using %s is forbidden, %s%s should be used.', $call, $forbiddenClass, $expectedCall);
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
    private function isForbiddenStaticMethodCall(File $phpcsFile, int $staticPointer, array $forbiddenMethods): bool
    {
        $tokens = $phpcsFile->getTokens();
        $methodCallPointer = $this->findStaticMethodPointer($phpcsFile, $staticPointer);

        if ($methodCallPointer === null) {
            return false;
        }

        if (in_array($tokens[$methodCallPointer]['content'], $forbiddenMethods, true)) {
            return true;
        }

        return false;
    }


    private function findStaticMethodPointer(File $phpcsFile, int $staticPointer): ?int
    {
        $methodCallPointer = TokenHelper::findNext($phpcsFile, [T_STRING], $staticPointer);

        if ($methodCallPointer === null) {
            return null;
        }

        $previousPointer = TokenHelper::findPreviousExcluding(
            $phpcsFile,
            TokenHelper::$ineffectiveTokenCodes,
            $methodCallPointer - 1
        );

        $previousToken = $phpcsFile->getTokens()[$previousPointer];
        if ($previousToken['code'] !== T_DOUBLE_COLON) {
            return null;
        }

        return $methodCallPointer;
    }


    /**
     * @return class-string|null
     */
    private function findForbiddenClass(string $className): ?string
    {
        foreach ($this->forbiddenClasses as $forbiddenClass) {
            if (is_a($className, $forbiddenClass, true)) {
                return $forbiddenClass;
            }
        }

        return null;
    }
}
