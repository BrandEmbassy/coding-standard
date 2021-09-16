<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Classes;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

final class ClassesWithoutSelfReferencingSniffTest extends TestCase
{
    public function testFixFile(): void
    {
        $file = __DIR__ . '/__fixtures__/classesWithoutSelfReferencing.php';
        require_once $file;

        $report = self::checkFile($file, ['classesWithoutSelfReferencing' => [Assert::class]]);

        Assert::assertSame(3, $report->getErrorCount());
        self::assertSniffError(
            $report,
            16,
            'ClassesWithoutSelfReferencing',
            'Using self::assertFalse is forbidden. Call PHPUnit\Framework\Assert::assertFalse directly.'
        );
        self::assertSniffError(
            $report,
            17,
            'ClassesWithoutSelfReferencing',
            'Using static::assertEquals is forbidden. Call PHPUnit\Framework\Assert::assertEquals directly.'
        );
        self::assertSniffError(
            $report,
            19,
            'ClassesWithoutSelfReferencing',
            'Using static::assertTrue is forbidden. Call PHPUnit\Framework\Assert::assertTrue directly.'
        );

        self::assertAllFixedInFile($report);
        Assert::assertSame(3, $report->getFixedCount());
    }
}
