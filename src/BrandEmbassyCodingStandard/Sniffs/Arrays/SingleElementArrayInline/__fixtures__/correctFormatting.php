<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\SingleElementArrayInline\__fixtures__;

// Empty arrays
$empty = [];

// Single element inline - correct
$a = ['one'];
$b = ['key' => 'value'];
$c = [1];

// Multi element multiline - not our concern
$d = [
    'one',
    'two',
    'three',
];

// Single element with nested array - skip (handled by NestedArrayMultiline)
$e = ['key' => ['nested']];

// Single element with multiline content - correct (cannot be inlined)
$f = [
    [
        'one',
        'two',
    ],
];

// Edge cases - correct
$g = foo(['single']);
$h = [...$items];
$i = [fn() => 'x'];
