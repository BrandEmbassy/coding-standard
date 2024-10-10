<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use MabeEnum\Enum;

class EnumWithHas extends Enum
{
    const USER = 'user';

    const UNSUPPORTED = 'unsupported';

    public static function getOrUnsupported(string $enumerator): self
    {
        if (self::has($enumerator)) {
            return self::get($enumerator);
        }

        if (EnumWithHas::has($enumerator)) {
            return EnumWithHas::get($enumerator);
        }

        return self::get(self::UNSUPPORTED);
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use MabeEnum\Enum;

class EnumWithHas extends Enum
{
    const USER = 'user';

    const UNSUPPORTED = 'unsupported';

    public static function getOrUnsupported(string $enumerator): self
    {
        if (self::tryFrom($enumerator) !== null) {
            return self::from($enumerator);
        }

        if (\BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture\EnumWithHas::tryFrom($enumerator) !== null) {
            return \BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture\EnumWithHas::from($enumerator);
        }

        return self::UNSUPPORTED;
    }
}
?>