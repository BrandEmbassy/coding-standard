<?php

use \Nette\Utils\Strings;

function() {
    $startsWith = \Nette\Utils\Strings::startsWith('haystack', 'needle');
    \Nette\Utils\Strings::startsWith('haystack', 'needle');
    $startsWith = Strings::startsWith('haystack', 'needle');
    Strings::startsWith('haystack', 'needle');
};

?>
-----
<?php

use \Nette\Utils\Strings;

function() {
    $startsWith = str_starts_with('haystack', 'needle');
    str_starts_with('haystack', 'needle');
    $startsWith = str_starts_with('haystack', 'needle');
    str_starts_with('haystack', 'needle');
};

?>
