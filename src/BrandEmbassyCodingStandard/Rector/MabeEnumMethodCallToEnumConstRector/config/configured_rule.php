<?php declare(strict_types = 1);

use BrandEmbassyCodingStandard\Rector\MabeEnumMethodCallToEnumConstRector\MabeEnumMethodCallToEnumConstRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(MabeEnumMethodCallToEnumConstRector::class, [
        'ignored_classes_regex' => '/.*IgnoredEnum/',
    ]);
};
