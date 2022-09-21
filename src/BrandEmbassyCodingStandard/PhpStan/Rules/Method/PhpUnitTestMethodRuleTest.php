<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<PhpUnitTestMethodRule>
 */
class PhpUnitTestMethodRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpUnitTestMethodRule($this->createReflectionProvider());
    }


    public function testAnalyseTestClasses(): void
    {
        $this->analyse(
            [
                __DIR__ . '/__fixtures__/BadlyNamedTestClass.php',
                __DIR__ . '/__fixtures__/NonPhpUnitTest.php',
                __DIR__ . '/__fixtures__/PhpUnitTest.php',
            ],
            [
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\BadlyNamedTestClass::testFunctionPublic() seems like a test method, but the class is not suffixed Test.',
                    10,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\BadlyNamedTestClass::testFunctionProtected() seems like a test method, but the class is not suffixed Test.',
                    16,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\BadlyNamedTestClass::testFunctionProtected() seems like a test method, but it is not public.',
                    16,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\BadlyNamedTestClass::testFunctionPrivate() seems like a test method, but the class is not suffixed Test.',
                    22,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\BadlyNamedTestClass::testFunctionPrivate() seems like a test method, but it is not public.',
                    22,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\NonPhpUnitTest::testFunctionPublic() seems like a test method, but the class is not a PHPUnit\Framework\TestCase.',
                    9,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\NonPhpUnitTest::testFunctionProtected() seems like a test method, but the class is not a PHPUnit\Framework\TestCase.',
                    15,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\NonPhpUnitTest::testFunctionProtected() seems like a test method, but it is not public.',
                    15,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\NonPhpUnitTest::testFunctionPrivate() seems like a test method, but the class is not a PHPUnit\Framework\TestCase.',
                    21,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\NonPhpUnitTest::testFunctionPrivate() seems like a test method, but it is not public.',
                    21,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\PhpUnitTest::testFunctionProtected() seems like a test method, but it is not public.',
                    16,
                ],
                [
                    'Method BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__\PhpUnitTest::testFunctionPrivate() seems like a test method, but it is not public.',
                    22,
                ],
            ],
        );
    }
}
