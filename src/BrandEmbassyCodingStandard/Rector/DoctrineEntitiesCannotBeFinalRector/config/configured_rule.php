<?php declare(strict_types = 1);

use BrandEmbassyCodingStandard\Rector\DoctrineEntitiesCannotBeFinalRector\DoctrineEntitiesCannotBeFinalRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DoctrineEntitiesCannotBeFinalRector::class);
};
