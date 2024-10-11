<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\NativeEnum;

/**
 * If MabeEnum was transformed to native enum in vendor repository, but the method calls in current repository are still using MabeEnum methods
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
 * If MabeEnum was transformed to native enum in vendor repository, but the method calls in current repository are still using MabeEnum methods
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
