<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

class DisallowConstantsInTestsRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    #[RunInSeparateProcess] // see README (Rector section) for why this is necessary
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
        return __DIR__ . '/config/config.php';
    }
}
