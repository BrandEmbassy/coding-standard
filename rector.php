<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

$rectorConfigBuilder = RectorConfig::configure();
$defaultRectorConfigurationSetup = require 'default-rector.php';

$defaultSkipList = $defaultRectorConfigurationSetup($rectorConfigBuilder);

$skipList = array_merge($defaultSkipList, [
    __DIR__ . "/**/__fixtures__/**",
    // Looks like rector bug, try removing later
    __DIR__ . "/src/BrandEmbassyCodingStandard/Sniffs/__fixtures/codeWithElseStatement.php",
    UseClassKeywordForClassNameResolutionRector::class,
    StringClassNameToClassConstantRector::class,
]);

$rectorConfigBuilder
    ->withPHPStanConfigs([__DIR__ . '/phpstan.neon'])
    ->withCache('./temp/rector', FileCacheStorage::class)
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSkip($skipList);

return $rectorConfigBuilder;