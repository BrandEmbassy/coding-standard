<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\ArrayFormatting\__fixtures__;

// Empty arrays
$empty = [];

// Single element inline - correct
$a = ['one'];
$b = ['key' => 'value'];
$c = [1];

// Multi element multiline - correct
$d = [
    'one',
    'two',
    'three',
];

$e = [
    'key1' => 'value1',
    'key2' => 'value2',
];

// Nested arrays - correct
$f = [
    ['one'],
    ['two'],
];

$g = [
    'key' => ['nested'],
];

// Single element with multiline content - correct (cannot be inlined)
$h = [
    [
        'one',
        'two',
    ],
];

// Edge cases - correct
$i = foo(['single']);
$j = [...$items];
$k = [fn() => 'x'];
foo([
    'a',
    'b',
]);
$l = [
    ...$items,
    ...$more,
];
