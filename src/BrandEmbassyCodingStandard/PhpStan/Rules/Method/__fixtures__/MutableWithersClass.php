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


    public function withString(string $string): self
    {
        $this->string = $string;

        return $this;
    }
}
