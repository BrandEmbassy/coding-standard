<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\vendor\IgnoredEnum;

final class IgnoredEnums {
    public function run(): void
    {
        IgnoredEnum::get(IgnoredEnum::USER);
        IgnoredEnum::get('user');

        IgnoredEnum::get('user')->is(IgnoredEnum::USER);
    }
}
?>
