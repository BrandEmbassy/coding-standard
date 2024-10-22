<?php
use MabeEnum\Enum;

/**
 * @method string getValue()
 * @method static UserType get(string $value)
 *
 * @final
 */
class BackedIntEnum extends Enum
{
    public const DEVIL = 666;
}
?>
-----
<?php
use MabeEnum\Enum;

enum BackedIntEnum : int
{
    use \BrandEmbassy\Utils\Enums\BackedEnumTrait;
    case DEVIL = 666;
}
?>
