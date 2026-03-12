<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\MultiElementArrayMultiline;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @final
 */
class MultiElementArrayMultilineSniffTest extends TestCase
{
    public function testCorrectFormattingProducesNoErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/correctFormatting.php');
        self::assertNoSniffErrorInFile($report);
    }


    public function testMultiElementSingleLineArraysAreFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/multiElementSingleLine.php');

        Assert::assertSame(3, $report->getErrorCount());

        self::assertSniffError($report, 5, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 7, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 9, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);

        self::assertAllFixedInFile($report);
    }


    public function testEdgeCasesAreFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/edgeCases.php');

        Assert::assertSame(6, $report->getErrorCount());

        self::assertSniffError($report, 5, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 8, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 11, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 14, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 17, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);
        self::assertSniffError($report, 20, MultiElementArrayMultilineSniff::CODE_MULTI_ELEMENT_NOT_MULTILINE);

        self::assertAllFixedInFile($report);
    }
}
