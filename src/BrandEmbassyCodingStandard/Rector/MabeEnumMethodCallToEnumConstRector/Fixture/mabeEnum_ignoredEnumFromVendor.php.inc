<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\vendor\IgnoredEnum;

final class IgnoredEnumsFromVendor {
    public function run(): void
    {
        IgnoredEnum::get(IgnoredEnum::USER);
        IgnoredEnum::get('user');

        IgnoredEnum::get('user')->is(IgnoredEnum::USER);
    }
}
?>
