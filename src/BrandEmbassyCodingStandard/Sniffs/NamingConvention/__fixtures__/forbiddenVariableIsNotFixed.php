<?php declare(strict_types = 1);

class Foo
{
    public function concat(Exception $e): string
    {
        $exception = new Exception();

        return $exception->getMessage() . $e->getMessage();
    }


    public function throwException(Exception $e): void
    {
        throw $e;
    }
}
