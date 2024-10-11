<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;
use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeObject;

final class CreateEnum {
    public function run() {
        $superUserConst = SomeEnum::SUPERUSER;

        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::get(SomeEnum::USER);
        SomeEnum::get(SomeEnum::USER);
        SomeEnum::get(SomeEnum::SUPERUSER);
        SomeEnum::SUPERUSER();
        SomeEnum::get('superuser');
        SomeEnum::get($superUserConst);

        $object = new SomeObject();
        SomeEnum::get($object->getValue());
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;
use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeObject;

final class CreateEnum {
    public function run() {
        $superUserConst = SomeEnum::SUPERUSER;

        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::USER;
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::USER;
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::SUPERUSER;
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::SUPERUSER;
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::from('superuser');
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::from($superUserConst);

        $object = new SomeObject();
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::from($object->getValue());
    }
}
?>
