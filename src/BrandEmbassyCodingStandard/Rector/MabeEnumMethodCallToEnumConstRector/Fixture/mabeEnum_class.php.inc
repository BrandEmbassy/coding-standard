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
    public static function getIndexedArray(): array
    {
        return [
            self::USER => 'user',
            self::SUPERUSER => 'superuser',
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
    public static function getIndexedArray(): array
    {
        return [
            self::USER->value => 'user',
            self::SUPERUSER->value => 'superuser',
        ];
    }
}
?>
