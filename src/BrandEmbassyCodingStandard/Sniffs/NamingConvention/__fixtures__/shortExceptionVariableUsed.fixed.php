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

function handle(RuntimeException $e)
{
    echo $e->getMessage();

    foreach (['a', 'b'] as $foo) {
        $exception = new Exception();
        echo $e->getMessage() . $foo;
    }
}
