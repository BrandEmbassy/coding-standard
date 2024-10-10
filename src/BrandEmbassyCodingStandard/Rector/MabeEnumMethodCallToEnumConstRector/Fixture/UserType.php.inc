<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use MabeEnum\Enum;

class UserType extends Enum
{
    public const USER = 'user';
    public const SUPERUSER = 'superuser';

    /**
     * @return string[]
     */
    public static function getEnumValues(): array
    {
        return [
            self::USER,
            self::SUPERUSER,
        ];
    }


    public function getNameAndValue(): string
    {
        return $this->getName() . ' ' . $this->getValue();
    }


    /**
     * @return string[]
     */
    public static function getSupportedUserTypes(): array
    {
        return [
            self::USER,
            self::SUPERUSER,
        ];
    }
}
?>
-----
<?php
namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Fixture;

use MabeEnum\Enum;

class UserType extends Enum
{
    public const USER = 'user';
    public const SUPERUSER = 'superuser';

    /**
     * @return string[]
     */
    public static function getEnumValues(): array
    {
        return [
            self::USER->value,
            self::SUPERUSER->value,
        ];
    }


    public function getNameAndValue(): string
    {
        return $this->name . ' ' . $this->value;
    }


    /**
     * @return string[]
     */
    public static function getSupportedUserTypes(): array
    {
        return [
            self::USER->value,
            self::SUPERUSER->value,
        ];
    }
}
?>
