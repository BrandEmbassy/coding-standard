<?php declare(strict_types = 1);

class Foo
{
    /**
     * @var Exception
     */
    private $e;


    public function __construct(Exception $e)
    {
        $this->e = $e;
    }


    public function getException(): Exception
    {
        return $this->e;
    }
}
