<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

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
    }
}