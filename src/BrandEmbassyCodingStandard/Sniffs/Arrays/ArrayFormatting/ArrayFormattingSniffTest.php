<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\ArrayFormatting;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @final
 */
class ArrayFormattingSniffTest extends TestCase
{
    public function testCorrectFormattingProducesNoErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/correctFormatting.php');
        self::assertNoSniffErrorInFile($report);
    }


    public function testSingleElementMultilineArraysAreFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/singleElementMultiline.php');

        Assert::assertSame(3, $report->getErrorCount());

        self::assertSniffError($report, 5, ArrayFormattingSniff::CODE_SINGLE_ELEMENT_NOT_INLINE);
        self::assertSniffError($report, 9, ArrayFormattingSniff::CODE_SINGLE_ELEMENT_NOT_INLINE);
        self::assertSniffError($report, 13, ArrayFormattingSniff::CODE_SINGLE_ELEMENT_NOT_INLINE);

        self::assertAllFixedInFile($report);
    }


    public function testMultiElementSingleLineArraysAreFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/multiElementSingleLine.php');

        Assert::assertSame(3, $report->getErrorCount());

        self::assertSniffError($report, 5, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 7, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 9, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);

        self::assertAllFixedInFile($report);
    }


    public function testNestedArraysAreFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/nestedArrays.php');

        Assert::assertSame(10, $report->getErrorCount());

        self::assertSniffError($report, 6, ArrayFormattingSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 9, ArrayFormattingSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 12, ArrayFormattingSniff::CODE_SINGLE_ELEMENT_NOT_INLINE);
        self::assertSniffError($report, 17, ArrayFormattingSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 20, ArrayFormattingSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 23, ArrayFormattingSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 26, ArrayFormattingSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 29, ArrayFormattingSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 29, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);

        self::assertAllFixedInFile($report);
    }


    public function testEdgeCasesAreFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/edgeCases.php');

        Assert::assertSame(7, $report->getErrorCount());

        self::assertSniffError($report, 6, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 9, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 12, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 15, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 18, ArrayFormattingSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 21, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 24, ArrayFormattingSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);

        self::assertAllFixedInFile($report);
    }
}
