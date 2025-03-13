<?php declare(strict_types = 1);

use BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\DisallowConstantsInTestsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(DisallowConstantsInTestsRector::class, [
        'allowedPatterns' => ['*_allowed_pattern.php'],
        'allowedConstants' => ['BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\Fixture\AllowedConstant'],
    ]);
