<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Commenting;

use PHPUnit\Framework\Assert;
use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @final
 */
class CreateMockFunctionReturnTypeOrderSniffTest extends TestCase
{
    public function testFinalClassIsFixed(): void
    {
        $report = self::checkFile(__DIR__ . '/__fixtures__/classWithCreateMockFunctions.php');

        Assert::assertSame(2, $report->getErrorCount());
        self::assertSniffError(
            $report,
            41,
            CreateMockFunctionReturnTypeOrderSniff::CODE_CREATE_MOCK_FUNCTION_RETURN_TYPE_ORDER,
        );
        self::assertSniffError(
            $report,
            51,
            CreateMockFunctionReturnTypeOrderSniff::CODE_CREATE_MOCK_FUNCTION_RETURN_TYPE_ORDER,
        );

        self::assertAllFixedInFile($report);
    }
}
