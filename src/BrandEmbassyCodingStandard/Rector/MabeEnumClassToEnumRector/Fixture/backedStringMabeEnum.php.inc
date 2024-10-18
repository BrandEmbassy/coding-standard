<?php
use MabeEnum\Enum;

/**
 * @method string getValue()
 * @method static BackedStringEnum get(string $value)
 *
 * @final
 */
class BackedStringEnum extends Enum
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

enum BackedStringEnum : string
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
