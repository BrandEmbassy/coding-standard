<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

final class ForbiddenStaticMethodCallSniffTest extends TestCase
{
    public function testFixWhenMethodIsFollowing(): void
    {
        $file = __DIR__ . '/__fixtures__/forbiddenStaticUsed.php';
        require_once $file;

        $report = self::checkFile($file, ['forbiddenClasses' => [Assert::class]]);

        Assert::assertSame(3, $report->getErrorCount());
        self::assertSniffError(
            $report,
            16,
            'ForbiddenStaticMethodCall',
            'Using self::assertFalse is forbidden. Call PHPUnit\Framework\Assert::assertFalse directly.'
        );
        self::assertSniffError(
            $report,
            17,
            'ForbiddenStaticMethodCall',
            'Using static::assertEquals is forbidden. Call PHPUnit\Framework\Assert::assertEquals directly.'
        );
        self::assertSniffError(
            $report,
            19,
            'ForbiddenStaticMethodCall',
            'Using static::assertTrue is forbidden. Call PHPUnit\Framework\Assert::assertTrue directly.'
        );

        self::assertAllFixedInFile($report);
        Assert::assertSame(3, $report->getFixedCount());
    }
}