<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum;

final class NativeEnumWithMabeEnumMethodCall
{
    public function run()
    {
        $nativeEnum = NativeEnum::UNKNOWN;
        $nativeEnum->getEnumValues();

        NativeEnum::has('user');
        NativeEnum::get('user');
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum;

final class NativeEnumWithMabeEnumMethodCall
{
    public function run()
    {
        $nativeEnum = NativeEnum::UNKNOWN;
        $nativeEnum->getEnumValues();

        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum::tryFrom('user') !== null;
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum::from('user');
    }
}
?>
