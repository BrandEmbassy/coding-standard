<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use RuntimeException;
use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @final
 */
class FinalClassByAnnotationSniffTest extends TestCase
{
    public function testFinalClassIsFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/finalClassByAnnotationWithFinalKeyword.php');

        Assert::assertSame(3, $report->getErrorCount());
        self::assertSniffError($report, 22, FinalClassByAnnotationSniff::CODE_FINAL_CLASS_BY_KEYWORD);
        self::assertSniffError($report, 33, FinalClassByAnnotationSniff::CODE_FINAL_CLASS_BY_KEYWORD);
        self::assertSniffError($report, 44, FinalClassByAnnotationSniff::CODE_FINAL_CLASS_BY_KEYWORD);

        self::assertAllFixedInFile($report);

        if (class_exists(Assert::class)) {
            $fixedFileContent = file_get_contents($report->getFilename());
            $expectedContent = file_get_contents(__DIR__ . '/__fixtures__/finalClassByAnnotationWithFinalKeyword.fixed.php');

            if ($fixedFileContent === false || $expectedContent === false) {
                throw new RuntimeException('Unable to read file for assertion.');
            }

            $fixedFileContent = str_replace(["\r\n", "\r"], "\n", $fixedFileContent);
            $expectedContent = str_replace(["\r\n", "\r"], "\n", $expectedContent);

            Assert::assertSame($expectedContent, $fixedFileContent);
        }
    }
}
