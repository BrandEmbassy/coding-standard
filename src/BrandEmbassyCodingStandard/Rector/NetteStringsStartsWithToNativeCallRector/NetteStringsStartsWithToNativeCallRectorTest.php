<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\NetteStringsStartsWithToNativeCallRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @final
 */
class NetteStringsStartsWithToNativeCallRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    #[RunInSeparateProcess]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }


    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }


    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
