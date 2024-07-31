<?php declare(strict_types = 1);

use BrandEmbassyCodingStandard\Rector\NetteStringsContainsToNativeCallRector\NetteStringsContainsToNativeCallRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(NetteStringsContainsToNativeCallRector::class);
};
