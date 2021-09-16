<?php declare(strict_types = 1);

class Foo
{
    /**
     * @var Exception
     */
    private $exception;


    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }


    public function getException(): Exception
    {
        return $this->exception;
    }
}
