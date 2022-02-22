<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @final
 */
class TraitUseSpacingSniffTest extends TestCase
{
    public function testFixWhenMethodIsFollowing(): void
    {
        $report = self::checkFile(
            __DIR__ . '/__fixtures__/traitUseSpacingWhenMethodIsFollowing.php',
            $this->getSniffProperties()
        );

        Assert::assertSame(1, $report->getErrorCount());

        self::assertSniffError($report, 9, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

        self::assertAllFixedInFile($report);
    }


    public function testFixWhenConstantIsFollowing(): void
    {
        $report = self::checkFile(
            __DIR__ . '/__fixtures__/traitUseSpacingWhenConstantIsFollowing.php',
            $this->getSniffProperties()
        );

        Assert::assertSame(1, $report->getErrorCount());

        self::assertSniffError($report, 9, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

        self::assertAllFixedInFile($report);
    }


    public function testFixWhenPropertyIsFollowing(): void
    {
        $report = self::checkFile(
            __DIR__ . '/__fixtures__/traitUseSpacingWhenConstantIsFollowing.php',
            $this->getSniffProperties()
        );

        Assert::assertSame(1, $report->getErrorCount());

        self::assertSniffError($report, 9, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

        self::assertAllFixedInFile($report);
    }


    /**
     * @return array<int, int>|int[]
     */
    private function getSniffProperties(): array
    {
        return [
            'linesCountBetweenUses' => 0,
            'linesCountBeforeFirstUse' => 0,
            'linesCountAfterLastUse' => 2,
            'linesCountAfterLastUseWhenLastInClass' => 0,
            'linesBeforeFollowingPropertyOrConstant' => 1,
        ];
    }
}
