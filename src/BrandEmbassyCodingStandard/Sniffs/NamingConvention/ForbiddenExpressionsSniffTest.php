<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\NamingConvention;

use SlevomatCodingStandard\Sniffs\TestCase;

final class ForbiddenExpressionsSniffTest extends TestCase
{
    public function testDummyIsForbidden(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/DummyTestClass.php');
        self::assertSniffError($report, 6, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 8, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 10, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 12, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 13, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 18, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 21, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 23, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 27, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 29, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 34, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 36, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 38, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 40, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 45, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
        self::assertSniffError($report, 47, ForbiddenExpressionsSniff::FORBIDDEN_EXPRESSION);
    }
}
