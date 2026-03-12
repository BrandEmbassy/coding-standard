<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\ArrayFormatting\__fixtures__;

// Array in function argument - multi-element
foo([
    'a',
    'b',
]);

// Array in method call - multi-element
$obj->method([
    'a',
    'b',
]);

// Spread operator - multi-element
$a = [
    ...$items,
    ...$more,
];

// Arrow functions - multi-element
$b = [
    fn() => 'x',
    fn() => 'y',
];

// Arrow function returning nested array - must be multiline
$c = [
    fn() => ['nested'],
];

// Inline comment between elements
$d = [
    'a' /* comment */,
    'b',
];

// Constant declaration
const FOO = [
    'a',
    'b',
];
