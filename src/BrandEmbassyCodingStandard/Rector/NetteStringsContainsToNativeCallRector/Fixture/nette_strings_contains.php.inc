<?php

use \Nette\Utils\Strings;

function() {
    $contains = \Nette\Utils\Strings::contains('haystack', 'needle');
    \Nette\Utils\Strings::contains('haystack', 'needle');
    $contains = Strings::contains('haystack', 'needle');
    Strings::contains('haystack', 'needle');
};

?>
-----
<?php

use \Nette\Utils\Strings;

function() {
    $contains = str_contains('haystack', 'needle');
    str_contains('haystack', 'needle');
    $contains = str_contains('haystack', 'needle');
    str_contains('haystack', 'needle');
};

?>
