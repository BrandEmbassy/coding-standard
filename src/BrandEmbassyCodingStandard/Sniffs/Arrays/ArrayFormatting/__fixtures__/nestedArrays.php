<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Arrays\ArrayFormatting\__fixtures__;

// Nested multi-element on single line - nested check fires first
$a = [['one'], ['two']];

// Single element with nested array inline - must be multiline
$b = ['key' => ['nested']];

// Single element with single-line content on multiple lines (no nesting)
$c = [
    'only-one',
];

// Deeply nested arrays - both outer and middle must be multiline
$d = ['key' => ['inner' => ['deep']]];

// Multi-element with nested array - nested check fires
$e = ['a', ['b']];

// Multi keyed with nested values inline - must be multiline
$f = ['k1' => ['a'], 'k2' => ['b']];

// Mixed: some values plain, some nested - must be multiline
$g = ['k1' => 'v1', 'k2' => ['nested']];

// Single keyed, value is multi-element array - outer must be multiline, inner must be multiline
$h = ['key' => ['a', 'b']];
