<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\NestedArrayMultiline;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @final
 */
class NestedArrayMultilineSniffTest extends TestCase
{
    public function testCorrectFormattingProducesNoErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/correctFormatting.php');
        self::assertNoSniffErrorInFile($report);
    }


    public function testNestedArraysAreFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/nestedArrays.php');

        Assert::assertSame(9, $report->getErrorCount());

        self::assertSniffError($report, 6, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 9, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 12, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 12, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 15, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 18, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 21, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 24, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);
        self::assertSniffError($report, 27, NestedArrayMultilineSniff::CODE_NESTED_NOT_MULTILINE);

        self::assertAllFixedInFile($report);
    }
}
