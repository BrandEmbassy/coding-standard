<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

final class ForbidSelfMethodCallSniffTest extends TestCase
{
    public function testFixWhenMethodIsFollowing(): void
    {
        $file = __DIR__ . '/__fixtures__/forbiddenSelfUsed.php';
        require_once $file;

        $report = self::checkFile($file);

//        Assert::assertSame(2, $report->getErrorCount());
        Assert::assertSame(2, $report->getFixedCount());

        self::assertAllFixedInFile($report);
    }
}
