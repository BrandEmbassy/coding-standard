<?php

use \Nette\Utils\DifferentClass;

function() {
    $startsWith = \Nette\Utils\DifferentClass::startsWith('haystack', 'needle');
    \Nette\Utils\DifferentClass::startsWith('haystack', 'needle');
    $startsWith = DifferentClass::startsWith('haystack', 'needle');
    DifferentClass::startsWith('haystack', 'needle');
};

?>
-----
<?php

use \Nette\Utils\DifferentClass;

function() {
    $startsWith = \Nette\Utils\DifferentClass::startsWith('haystack', 'needle');
    \Nette\Utils\DifferentClass::startsWith('haystack', 'needle');
    $startsWith = DifferentClass::startsWith('haystack', 'needle');
    DifferentClass::startsWith('haystack', 'needle');
};

?>
