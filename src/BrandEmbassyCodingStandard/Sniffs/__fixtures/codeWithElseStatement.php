<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\__fixtures;

use Mockery\MockInterface;

class CreateMockFunctions
{
    private function withNoPhpDoc(): void
    {
        if (true) {
            echo 'bar';
        } elseif (true) {
            echo 'lol';
        } else {
            echo 'foo';
        }
    }
}
