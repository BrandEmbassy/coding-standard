<?php

use \Nette\Utils\DifferentClass;

function() {
    $contains = \Nette\Utils\DifferentClass::contains('haystack', 'needle');
    \Nette\Utils\DifferentClass::contains('haystack', 'needle');
    $contains = DifferentClass::contains('haystack', 'needle');
    DifferentClass::contains('haystack', 'needle');
};

?>
-----
<?php

use \Nette\Utils\DifferentClass;

function() {
    $contains = \Nette\Utils\DifferentClass::contains('haystack', 'needle');
    \Nette\Utils\DifferentClass::contains('haystack', 'needle');
    $contains = DifferentClass::contains('haystack', 'needle');
    DifferentClass::contains('haystack', 'needle');
};

?>
