<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method\__fixtures__;

final class ImmutableWithersClass
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
        $clone = clone $this;
        $clone->string = $string;

        return $clone;
    }


    public function withPrefixedString(string $string): self
    {
        return $this->withString('prefix_' . $string);
    }
}
