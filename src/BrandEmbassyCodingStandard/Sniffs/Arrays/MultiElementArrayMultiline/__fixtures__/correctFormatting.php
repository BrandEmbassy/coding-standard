<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\MultiElementArrayMultiline\__fixtures__;

// Empty arrays
$empty = [];

// Single element inline - not our concern
$a = ['one'];
$b = ['key' => 'value'];

// Multi element multiline - correct
$c = [
    'one',
    'two',
    'three',
];

$d = [
    'key1' => 'value1',
    'key2' => 'value2',
];

// Edge cases - correct multiline
foo([
    'a',
    'b',
]);

$e = [
    ...$items,
    ...$more,
];
