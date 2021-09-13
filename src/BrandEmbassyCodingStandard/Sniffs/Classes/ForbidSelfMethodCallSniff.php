<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPUnit\Framework\Assert;
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

final class ForbidSelfMethodCallSniff implements Sniff
{
    private const FORBIDDEN_CLASSES = [
        Assert::class,
    ];


    public function register(): array
    {
        return [
            T_SELF,
            T_STATIC,
        ];
    }


    public function process(File $phpcsFile, $tokenPointer): void
    {
        $classPointer = TokenHelper::findPrevious($phpcsFile, [T_CLASS], $tokenPointer);
        $className = ClassHelper::getFullyQualifiedName($phpcsFile, $classPointer);

        $forbiddenClass = $this->findForbiddenClass($className);

        if ($forbiddenClass === null) {
            return;
        }

        $this->checkForbiddenClass($phpcsFile, $tokenPointer, $forbiddenClass);
    }


    private function checkForbiddenClass(File $phpcsFile, int $extendPointer, string $forbiddenClass): void
    {
        $reflection = new ReflectionClass($forbiddenClass);
        $methods = array_map(static function (ReflectionMethod $method): string {
            return $method->name;
        }, $reflection->getMethods(ReflectionMethod::IS_STATIC));

        $methodCallPointers = $this->getSelfOrStaticCalls($phpcsFile, $extendPointer, $methods);

        foreach ($methodCallPointers as $methodCallPointer) {
            $this->addMethodCallError($phpcsFile, $methodCallPointer, $forbiddenClass);
        }
    }


    private function addMethodCallError(
        File $phpcsFile,
        int $methodCallPointer,
        string $forbiddenClass
    ): void {
        $methodNamePointer = $methodCallPointer + 2;
        $call = TokenHelper::getContent($phpcsFile, $methodCallPointer, $methodNamePointer);
        $expectedCall = TokenHelper::getContent($phpcsFile, $methodCallPointer + 1, $methodNamePointer);

        $errorMessage = sprintf('Using %s is forbidden, %s%s should be used.', $call, $forbiddenClass, $expectedCall);
        $fix = $phpcsFile->addError(
            $errorMessage,
            $methodCallPointer,
            'ForbiddenSelfMethodCall'
        );

        if (!$fix) {
            return;
        }

        if (!StringHelper::startsWith($forbiddenClass, '\\')) {
            $forbiddenClass = '\\' . $forbiddenClass;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->replaceToken($methodCallPointer, $forbiddenClass);
        $phpcsFile->fixer->endChangeset();
    }


    private function getSelfOrStaticCalls(File $phpcsFile, int $classPointer, array $forbiddenMethods): array
    {
        $tokens = $phpcsFile->getTokens();
        $selfPointers = TokenHelper::findNextAll($phpcsFile, [T_SELF, T_STATIC], $classPointer);

        $assertionPointer = [];

        foreach ($selfPointers as $selfPointer) {
            $methodCallPointer = TokenHelper::findNext($phpcsFile, [T_STRING], $selfPointer);

            if($methodCallPointer === null){
                continue;
            }

            $previousPointer = TokenHelper::findPreviousExcluding(
                $phpcsFile,
                TokenHelper::$ineffectiveTokenCodes,
                $methodCallPointer - 1
            );

            $previousToken = $tokens[$previousPointer];
            if($previousToken['code'] !== T_DOUBLE_COLON){
                continue;
            }

            if (in_array($tokens[$methodCallPointer]['content'], $forbiddenMethods, true)) {
                $assertionPointer[] = $selfPointer;
            }
        }

        return $assertionPointer;
    }


    private function findForbiddenClass(string $className): ?string
    {
        foreach (self::FORBIDDEN_CLASSES as $forbiddenClass) {
            if (is_a($className, $forbiddenClass, true)) {
                return $forbiddenClass;
            }
        }

        return null;
    }
}
