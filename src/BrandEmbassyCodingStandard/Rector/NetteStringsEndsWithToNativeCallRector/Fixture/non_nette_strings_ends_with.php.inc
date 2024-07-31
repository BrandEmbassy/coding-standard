<?php

use \Nette\Utils\DifferentClass;

function() {
    $endsWith = \Nette\Utils\DifferentClass::endsWith('haystack', 'needle');
    \Nette\Utils\DifferentClass::endsWith('haystack', 'needle');
    $endsWith = DifferentClass::endsWith('haystack', 'needle');
    DifferentClass::endsWith('haystack', 'needle');
};

?>
-----
<?php

use \Nette\Utils\DifferentClass;

function() {
    $endsWith = \Nette\Utils\DifferentClass::endsWith('haystack', 'needle');
    \Nette\Utils\DifferentClass::endsWith('haystack', 'needle');
    $endsWith = DifferentClass::endsWith('haystack', 'needle');
    DifferentClass::endsWith('haystack', 'needle');
};

?>
