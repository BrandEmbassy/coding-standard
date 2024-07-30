<?php declare(strict_types = 1);

use BrandEmbassyCodingStandard\Rector\NetteStringsStartsWithToNativeCallRector\NetteStringsStartsWithToNativeCallRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(NetteStringsStartsWithToNativeCallRector::class);
};
