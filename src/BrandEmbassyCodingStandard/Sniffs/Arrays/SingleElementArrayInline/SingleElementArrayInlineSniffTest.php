<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\SingleElementArrayInline;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @final
 */
class SingleElementArrayInlineSniffTest extends TestCase
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

        self::assertSniffError($report, 5, SingleElementArrayInlineSniff::CODE_SINGLE_ELEMENT_NOT_INLINE);
        self::assertSniffError($report, 9, SingleElementArrayInlineSniff::CODE_SINGLE_ELEMENT_NOT_INLINE);
        self::assertSniffError($report, 13, SingleElementArrayInlineSniff::CODE_SINGLE_ELEMENT_NOT_INLINE);

        self::assertAllFixedInFile($report);
    }
}
