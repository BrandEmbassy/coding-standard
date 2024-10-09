<?php
use MabeEnum\Enum;

/**
 * @method string getValue()
 * @method static UserType get(string $value)
 *
 * @final
 */
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
}
?>
-----
<?php
use MabeEnum\Enum;

enum UserType : string
{
    use \BrandEmbassy\Utils\Enums\EnumTrait;
    case USER = 'user';
    case SUPERUSER = 'superuser';
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
}
?>
