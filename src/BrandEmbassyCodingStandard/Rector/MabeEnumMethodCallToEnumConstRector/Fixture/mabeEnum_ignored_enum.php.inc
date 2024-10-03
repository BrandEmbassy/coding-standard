<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\IgnoredEnum;

final class IgnoredEnum {
    public function run() {
        $user = IgnoredEnum::get(IgnoredEnum::USER);

        $user->getValue();
        $user->getName();

        IgnoredEnum::get(IgnoredEnum::USER)->getValue();
        IgnoredEnum::get(IgnoredEnum::USER)->getName();
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\IgnoredEnum;

final class IgnoredEnum {
    public function run() {
        $user = IgnoredEnum::get(IgnoredEnum::USER);

        $user->getValue();
        $user->getName();

        IgnoredEnum::get(IgnoredEnum::USER)->getValue();
        IgnoredEnum::get(IgnoredEnum::USER)->getName();
    }
}
?>
