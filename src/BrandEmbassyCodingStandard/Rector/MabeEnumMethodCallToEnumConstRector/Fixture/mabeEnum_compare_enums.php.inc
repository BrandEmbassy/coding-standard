<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class EnumComparison {
    public function run() {
        $user = SomeEnum::get(SomeEnum::USER);
        $superuser = SomeEnum::get(SomeEnum::SUPERUSER);

        $user->is($superuser);
        $user->is(SomeEnum::get(SomeEnum::USER));
        $user->is(SomeEnum::USER);
        SomeEnum::get(SomeEnum::SUPERUSER)->is(SomeEnum::SUPERUSER);
        SomeEnum::get(SomeEnum::SUPERUSER)->is(SomeEnum::get(SomeEnum::SUPERUSER));
        SomeEnum::get(SomeEnum::SUPERUSER)->is($user);
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;

final class EnumComparison {
    public function run() {
        $user = \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::USER;
        $superuser = \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::SUPERUSER;

        $user === $superuser;
        $user === SomeEnum::USER;
        $user === SomeEnum::USER;
        SomeEnum::SUPERUSER === SomeEnum::SUPERUSER;
        SomeEnum::SUPERUSER === SomeEnum::SUPERUSER;
        SomeEnum::SUPERUSER === $user;
    }
}
?>
