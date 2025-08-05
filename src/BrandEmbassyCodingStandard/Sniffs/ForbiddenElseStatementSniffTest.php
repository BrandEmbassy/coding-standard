<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs;

use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @final
 */
class ForbiddenElseStatementSniffTest extends TestCase
{
    public function testErrorIsAdded(): void
    {
        $file = __DIR__ . '/__fixtures__/codeWithElseStatement.php';
        require_once $file;

        $report = self::checkFile($file);

        self::assertSniffError(
            $report,
            13,
            'ForbiddenElseStatement',
            'Use of elseif is forbidden.',
        );

        self::assertSniffError(
            $report,
            15,
            'ForbiddenElseStatement',
            'Use of else is forbidden.',
        );
    }
}
