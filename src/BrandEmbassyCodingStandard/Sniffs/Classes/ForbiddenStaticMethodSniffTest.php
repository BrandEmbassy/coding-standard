<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

final class ForbiddenStaticMethodSniffTest extends TestCase
{
    public function testFixWhenMethodIsFollowing(): void
    {
        $file = __DIR__ . '/__fixtures__/forbiddenStaticUsed.php';
        require_once $file;

        $report = self::checkFile($file, ['forbiddenClasses' => [Assert::class]]);

        Assert::assertSame(3, $report->getErrorCount());

        self::assertAllFixedInFile($report);
        Assert::assertSame(3, $report->getFixedCount());
    }
}
