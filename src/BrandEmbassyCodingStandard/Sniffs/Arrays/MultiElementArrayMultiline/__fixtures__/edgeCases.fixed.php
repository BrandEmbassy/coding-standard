<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\MultiElementArrayMultiline\__fixtures__;

foo([
    'a',
    'b',
]);

// Array in method call
$obj->method([
    'a',
    'b',
]);

// Spread operator
$a = [
    ...$items,
    ...$more,
];

// Arrow functions
$b = [
    fn() => 'x',
    fn() => 'y',
];

// Inline comment between elements
$c = [
    'a' /* comment */,
    'b',
];

// Constant declaration
const FOO = [
    'a',
    'b',
];
