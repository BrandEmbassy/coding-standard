<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class CreateEnum {
    public function run() {
        SomeEnum::getEnumerators();
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class CreateEnum {
    public function run() {
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::cases();
    }
}
?>