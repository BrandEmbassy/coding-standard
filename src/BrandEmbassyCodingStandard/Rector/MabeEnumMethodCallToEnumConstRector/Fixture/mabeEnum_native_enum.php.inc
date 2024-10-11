<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum;

/**
 * If MabeEnum from vendor was transformed to native enum but current repository is outdated (uses MabeEnum calls)
 */
final class NativeEnumWithMabeEnumMethodCall
{
    public function run()
    {
        $nativeEnum = NativeEnum::UNKNOWN;
        $nativeEnum->getValue();

        NativeEnum::has('user');
        NativeEnum::get('user');
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum;

/**
 * If MabeEnum from vendor was transformed to native enum but current repository is outdated (uses MabeEnum calls)
 */
final class NativeEnumWithMabeEnumMethodCall
{
    public function run()
    {
        $nativeEnum = NativeEnum::UNKNOWN;
        $nativeEnum->value;

        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum::tryFrom('user') !== null;
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum::from('user');
    }
}
?>
