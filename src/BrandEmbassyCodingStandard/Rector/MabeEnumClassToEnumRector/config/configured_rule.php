<?php declare(strict_types = 1);

use BrandEmbassyCodingStandard\Rector\MabeEnumClassToEnumRector\MabeEnumClassToEnumRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MabeEnumClassToEnumRector::class);
};
