<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfContinueToMultiContinueRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

/**
 * @return mixed[] default skip list
 */
return static function (RectorConfig $rectorConfig): array {
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_74,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::CODING_STYLE,
        SetList::TYPE_DECLARATION,
        SetList::PRIVATIZATION,
        SetList::EARLY_RETURN,
    ]);

    if (!defined('RECTOR_IS_RUNNING')) {
        define('RECTOR_IS_RUNNING', 1);
    }

    $maxProcesses = getenv('RECTOR_MAX_PROCESSES');
    $maxProcesses = $maxProcesses === false ? 16 : (int) $maxProcesses;

    $rectorConfig->parallel(120, $maxProcesses);
    $rectorConfig->importNames();

    return [
        FinalizeClassesWithoutChildrenRector::class,
        // Following rules break down the && and || for early returns / continue, which is not always desired
        ChangeAndIfToEarlyReturnRector::class,
        ChangeOrIfContinueToMultiContinueRector::class,
        ReturnBinaryOrToEarlyReturnRector::class,
        CatchExceptionNameMatchingTypeRector::class,
        UnSpreadOperatorRector::class,
        // @see \PHPStan\Rules\DisallowedConstructs\DisallowedShortTernaryRule
        // Short ternary operator is not allowed. Use null coalesce operator if applicable or consider using long ternary.
        TernaryToElvisRector::class,
        // We don't want this because sometimes number is intentionally broken down into x * y * z for better readability
        RemoveDeadZeroAndOneOperationRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
    ];
};
