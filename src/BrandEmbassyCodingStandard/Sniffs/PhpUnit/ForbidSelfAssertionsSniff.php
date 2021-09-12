<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\PhpUnit;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\StringHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_map;
use function in_array;
use const T_EXTENDS;
use const T_SELF;
use const T_STRING;

final class ForbidSelfAssertionsSniff implements Sniff
{
    private const FORBIDDEN_CLASSES = [
        '\PHPUnit\Framework\Assert',
    ];
    private const FORBIDDEN_PARENTS = [
        TestCase::class,
    ];


    public function register(): array
    {
        return [
            T_EXTENDS,
        ];
    }


    public function process(File $phpcsFile, $extendPointer): void
    {
        $tokens = $phpcsFile->getTokens();
        $parentClassPointer = TokenHelper::findNext($phpcsFile, [T_STRING], $extendPointer);
        $parentClass = NamespaceHelper::resolveClassName(
            $phpcsFile,
            $tokens[$parentClassPointer]['content'],
            $extendPointer
        );

        if (!in_array($parentClass, $this->getForbiddenParents(), true)) {
            return;
        }

        foreach (self::FORBIDDEN_CLASSES as $forbiddenClass) {
            $this->checkForbiddenClass($phpcsFile, $extendPointer, $forbiddenClass);
        }
    }


    private function checkForbiddenClass(File $phpcsFile, int $extendPointer, string $forbiddenClass): void
    {
        $reflection = new ReflectionClass($forbiddenClass);
        $methods = array_map(static function (ReflectionMethod $method): string {
            return $method->name;
        }, $reflection->getMethods(ReflectionMethod::IS_STATIC));

        $assertionTokens = $this->getAssertionPointers($phpcsFile, $extendPointer, $methods);

        foreach ($assertionTokens as $methodCallPointer) {
            $fix = $phpcsFile->addError('Using self:: is forbidden.', $methodCallPointer, 'ForbiddenSelfMethodCall');

            if (!$fix) {
                continue;
            }

            $phpcsFile->fixer->beginChangeset();
            $phpcsFile->fixer->replaceToken($methodCallPointer, $forbiddenClass);
            $phpcsFile->fixer->endChangeset();
        }
    }


    private function getAssertionPointers(File $phpcsFile, int $classPointer, array $forbiddenMethods): array
    {
        $tokens = $phpcsFile->getTokens();
        $selfPointers = TokenHelper::findNextAll($phpcsFile, [T_SELF], $classPointer);

        $assertionPointer = [];

        foreach ($selfPointers as $selfPointer) {
            $methodCallPointer = TokenHelper::findNext($phpcsFile, [T_STRING], $selfPointer);

            if (in_array($tokens[$methodCallPointer]['content'], $forbiddenMethods, true)) {
                $assertionPointer[] = $selfPointer;
            }
        }

        return $assertionPointer;
    }


    private function getForbiddenParents(): array
    {
        return array_map(static function (string $parent): string {
            if (!StringHelper::startsWith($parent, "\\")) {
                return "\\" . $parent;
            }

            return $parent;
        }, self::FORBIDDEN_PARENTS);
    }
}
