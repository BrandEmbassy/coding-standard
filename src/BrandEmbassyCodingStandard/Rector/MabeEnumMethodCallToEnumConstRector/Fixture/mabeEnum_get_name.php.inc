<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class GetName {
    public function run() {
        $user = SomeEnum::get(SomeEnum::USER);
        $user->getName();
        SomeEnum::get(SomeEnum::USER)->getName();
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class GetName {
    public function run() {
        $user = \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::USER;
        $user->name;
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::USER->name;
    }
}
?>
