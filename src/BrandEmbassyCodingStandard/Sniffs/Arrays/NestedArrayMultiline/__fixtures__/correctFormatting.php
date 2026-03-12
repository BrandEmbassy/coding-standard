<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\NestedArrayMultiline\__fixtures__;

// Empty arrays
$empty = [];

// Single element inline without nesting - not our concern
$a = ['one'];

// Multi element without nesting - not our concern
$b = ['one', 'two'];

// Nested arrays already multiline - correct
$c = [
    ['one'],
    ['two'],
];

$d = [
    'key' => ['nested'],
];

$e = [
    'key' => [
        'inner' => ['deep'],
    ],
];

// Arrow function returning nested array - already multiline
$f = [
    fn() => ['nested'],
];
