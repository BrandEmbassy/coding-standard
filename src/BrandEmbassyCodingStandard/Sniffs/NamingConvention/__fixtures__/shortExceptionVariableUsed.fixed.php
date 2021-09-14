<?php declare(strict_types = 1);

try {
    assert(true);
} catch (Exception $exception) {
    echo $exception->getMessage();
}

try {
    assert(true);
} catch (Throwable $exception) {
    echo $exception->getMessage();
}

try {
    assert(true);
} catch (RuntimeException | LogicException $exception) {
    echo $exception->getMessage();
}

function handle(RuntimeException $exception)
{
    echo $exception->getMessage();
}
