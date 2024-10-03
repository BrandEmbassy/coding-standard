<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\Source;

use MabeEnum\Enum;

/**
 * @method string getValue()
 * @method static IgnoredEnum get(string $value)
 *
 * @final
 */
class IgnoredEnum extends Enum
{
    public const USER = 'user';

    public const SUPERUSER = 'superuser';
}
