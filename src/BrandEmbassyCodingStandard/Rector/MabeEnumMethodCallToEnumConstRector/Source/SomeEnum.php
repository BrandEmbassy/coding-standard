<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source;

use MabeEnum\Enum;

/**
 * @method string getValue()
 * @method static SomeEnum get(string $value)
 *
 * @final
 */
class SomeEnum extends Enum
{
    public const USER = 'user';

    public const SUPERUSER = 'superuser';


    public function isSuperUser(): bool
    {
        return $this->is(self::SUPERUSER);
    }


    public function isUser(): bool
    {
        return $this->is(self::USER);
    }
}
