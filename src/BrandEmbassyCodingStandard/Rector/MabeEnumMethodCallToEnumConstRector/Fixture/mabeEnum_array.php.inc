<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class Arrays {
    public function run() {
        /** @var string[] $array */
        $array = [
            SomeEnum::USER,
            SomeEnum::SUPERUSER,
        ];
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class Arrays {
    public function run() {
        /** @var string[] $array */
        $array = [
            SomeEnum::USER->value,
            SomeEnum::SUPERUSER->value,
        ];
    }
}
?>
