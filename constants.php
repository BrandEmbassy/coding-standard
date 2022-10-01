<?php declare(strict_types = 1);

// phpstan expects these constants to be an integer, but cs defines it as string
// see vendor/squizlabs/php_codesniffer/src/Util/Tokens.php
// Its hard to tell if this is a phpstan bug due to working with php8 constants in php7.4 project,
// or if its a cs bug for declaring them as strings when in php8 they are integers
if (defined('T_READONLY') === false) {
    define('T_READONLY', 327);
}

if (defined('T_MATCH') === false) {
    define('T_MATCH', 306);
}
