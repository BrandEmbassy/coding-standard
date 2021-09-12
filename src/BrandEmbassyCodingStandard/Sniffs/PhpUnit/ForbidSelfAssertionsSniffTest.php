<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\PhpUnit;
use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

final class ForbidSelfAssertionsSniffTest extends TestCase
{
    public function testFixWhenMethodIsFollowing(): void
    {
        $report = self::checkFile(
            __DIR__ . '/__fixtures__/forbiddenSelfUsed.php',
        );

        Assert::assertSame(1, $report->getErrorCount());

        self::assertAllFixedInFile($report);
    }
}
