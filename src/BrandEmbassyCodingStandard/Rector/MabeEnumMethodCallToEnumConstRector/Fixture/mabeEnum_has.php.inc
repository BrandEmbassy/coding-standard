<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;
use MabeEnum\Enum;

final class Enumerators {
    public function run() {
        SomeEnum::has('user');
    }
}

class MyEnum extends Enum
{
    const USER = 'user';

    const UNSUPPORTED = 'unsupported';

    public static function getOrUnsupported(string $enumerator): self
    {
        if (self::has($enumerator)) {
            return self::get($enumerator);
        }

        return self::get(self::UNSUPPORTED);
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum;
use MabeEnum\Enum;

final class Enumerators {
    public function run() {
        \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source\SomeEnum::tryFrom('user') !== null;
    }
}

class MyEnum extends Enum
{
    const USER = 'user';

    const UNSUPPORTED = 'unsupported';

    public static function getOrUnsupported(string $enumerator): self
    {
        if (self::tryFrom($enumerator) !== null) {
            return self::from($enumerator);
        }

        return self::UNSUPPORTED;
    }
}
?>
