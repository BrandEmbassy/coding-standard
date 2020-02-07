<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

final class TraitUsePositionSniffTest extends TestCase
{
    public function testNoUses(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/traitUsePositionNoTraitUses.php');
        self::assertNoSniffErrorInFile($report);
    }


    public function testUseWithConstants(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/traitUsePositionTraitWithConstants.php');

        Assert::assertSame(1, $report->getErrorCount());

        self::assertSniffError($report, 3, TraitUsePositionSniff::CODE_TRAIT_USE_IS_NOT_FIRST_IN_CLASS);

        self::assertAllFixedInFile($report);
    }
}
