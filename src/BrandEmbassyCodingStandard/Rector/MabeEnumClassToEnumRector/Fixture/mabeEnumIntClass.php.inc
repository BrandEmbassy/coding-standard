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
    public const DEVIL = 666;
}
?>
-----
<?php
use MabeEnum\Enum;

enum UserType : int
{
    case DEVIL = 666;
}
?>
