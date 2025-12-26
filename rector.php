<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassLike\RemoveTypedPropertyNonMockDocblockRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets(php82: true)
    ->withImportNames(removeUnusedImports: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        earlyReturn: true,
        typeDeclarations: true
    )
    ->withRules([
        ClassPropertyAssignToConstructorPromotionRector::class,
    ])
    ->withSkip([
        RemoveTypedPropertyNonMockDocblockRector::class,
        RemoveUselessVarTagRector::class,
        RemoveUselessReadOnlyTagRector::class,
        RemoveUselessReturnTagRector::class,
        RemoveUselessParamTagRector::class,
        RemoveNullTagValueNodeRector::class,
    ]);
