<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__;

final class MutableWithersClass
{
    /**
     * @var string
     */
    private $string;


    public function getString(): string
    {
        return $this->string;
    }


    public function setString(string $string): void
    {
        $this->string = $string;
    }


    public function withStringSetter(string $string): self
    {
        $this->setString($string);

        return clone $this;
    }


    public function withString(string $string): self
    {
        $clone = clone $this;
        $clone->string = $string;

        return $this;
    }


    public function withStringAssignment(string $string): void
    {
        $this->string = $string;
    }
}
