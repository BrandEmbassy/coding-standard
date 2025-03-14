<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

/**
 * @final
 */
class MabeEnumMethodCallToEnumConstRectorTest extends AbstractRectorTestCase implements MinPhpVersionInterface
{
    #[DataProvider('provideData')]
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


    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::STR_CONTAINS;
    }
}
