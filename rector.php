<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return static function (RectorConfig $rectorConfig): void {
    $defaultRectorConfigurationSetup = require 'default-rector.php';

    $defaultSkipList = $defaultRectorConfigurationSetup($rectorConfig);

    $rectorConfig->phpVersion(PhpVersion::PHP_74);
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');
    $rectorConfig->cacheClass(FileCacheStorage::class);
    $rectorConfig->cacheDirectory('./temp/rector');

    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $defaultSkipList = array_merge($defaultSkipList, [
        __DIR__ . "/**/__fixtures__/**",
        UseClassKeywordForClassNameResolutionRector::class,
        StringClassNameToClassConstantRector::class,
    ]);

    $rectorConfig->skip($defaultSkipList);
};
