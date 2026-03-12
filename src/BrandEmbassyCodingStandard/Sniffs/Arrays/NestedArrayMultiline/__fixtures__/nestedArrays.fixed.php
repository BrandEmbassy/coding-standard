<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\NestedArrayMultiline\__fixtures__;

// Multi-element with nested arrays on single line
$a = [
    ['one'],
    ['two'],
];

// Single element with nested array inline
$b = [
    'key' => ['nested'],
];

// Deeply nested arrays
$c = [
    'key' => [
        'inner' => ['deep'],
    ],
];

// Multi-element with nested array
$d = [
    'a',
    ['b'],
];

// Multi keyed with nested values inline
$e = [
    'k1' => ['a'],
    'k2' => ['b'],
];

// Mixed: some values plain, some nested
$f = [
    'k1' => 'v1',
    'k2' => ['nested'],
];

// Single keyed, value is multi-element array
$g = [
    'key' => ['a', 'b'],
];

// Arrow function returning nested array on single line
$h = [
    fn() => ['nested'],
];
