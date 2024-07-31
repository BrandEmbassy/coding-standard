<?php

use \Nette\Utils\Strings;

function() {
    $endsWith = \Nette\Utils\Strings::endsWith('haystack', 'needle');
    \Nette\Utils\Strings::endsWith('haystack', 'needle');
    $endsWith = Strings::endsWith('haystack', 'needle');
    Strings::endsWith('haystack', 'needle');
};

?>
-----
<?php

use \Nette\Utils\Strings;

function() {
    $endsWith = str_ends_with('haystack', 'needle');
    str_ends_with('haystack', 'needle');
    $endsWith = str_ends_with('haystack', 'needle');
    str_ends_with('haystack', 'needle');
};

?>
