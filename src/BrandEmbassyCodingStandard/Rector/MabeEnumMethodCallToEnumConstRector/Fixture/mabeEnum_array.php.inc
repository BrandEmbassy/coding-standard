<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class Enumerators {
    public function run() {
        /** @var string[] $array */
        $array = [
            SomeEnum::USER,
            SomeEnum::SUPERUSER,
        ];

        /** @var string[] $indexedArray */
        $indexedArray = [
            SomeEnum::USER => 'user',
            SomeEnum::SUPERUSER => 'superuser',
        ];
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class Enumerators {
    public function run() {
        /** @var string[] $array */
        $array = [
            SomeEnum::USER->value,
            SomeEnum::SUPERUSER->value,
        ];

        /** @var string[] $indexedArray */
        $indexedArray = [
            SomeEnum::USER->value => 'user',
            SomeEnum::SUPERUSER->value => 'superuser',
        ];
    }
}
?>
