<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\NamingConvention;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

final class ForbiddenVariableNameSniffTest extends TestCase
{
    /**
     * @dataProvider filesToFixDataProvider
     */
    public function testFixWhenMethodIsFollowing(
        int $expectedErrorCountBeforeFix,
        int $expectedErrorCountAfterFix,
        int $expectedFixedCount,
        string $fileToCheck
    ): void {
        $report = self::checkFile($fileToCheck, [
            'forbiddenVariableNames' => [
                '$e' => '$exception',
                '$t' => '$exception',
            ],
        ]);

        Assert::assertSame($expectedErrorCountBeforeFix, $report->getErrorCount());

        self::assertAllFixedInFile($report);
        Assert::assertSame($expectedErrorCountAfterFix, $report->getErrorCount());
        Assert::assertSame($expectedFixedCount, $report->getFixedCount());
    }


    /**
     * @return mixed[][]
     */
    public function filesToFixDataProvider(): array
    {
        return [
            'simple use in functions' => [
                'expectedErrorCountBeforeFix' => 8,
                'expectedErrorCountAfterFix' => 0,
                'expectedFixedCount' => 8,
                'fileToCheck' => __DIR__ . '/__fixtures__/shortExceptionVariableUsed.php',
            ],
            'as class property' => [
                'expectedErrorCountBeforeFix' => 3,
                'expectedErrorCountAfterFix' => 0,
                'expectedFixedCount' => 3,
                'fileToCheck' => __DIR__ . '/__fixtures__/forbiddenVariableAsClassProperty.php',
            ],
            'can not be replaced' => [
                'expectedErrorCountBeforeFix' => 4,
                'expectedErrorCountAfterFix' => 2,
                'expectedFixedCount' => 2,
                'fileToCheck' => __DIR__ . '/__fixtures__/forbiddenVariableIsNotFixed.php',
            ],
        ];
    }
}
