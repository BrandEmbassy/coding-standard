<?php declare(strict_types = 1);

try {
    assert(true);
} catch (Exception $e) {
    echo $e->getMessage();
}

try {
    assert(true);
} catch (Throwable $t) {
    echo $t->getMessage();
}

try {
    assert(true);
} catch (RuntimeException | LogicException $t) {
    echo $t->getMessage();
}

function handle(RuntimeException $e)
{
    echo $e->getMessage();
}
