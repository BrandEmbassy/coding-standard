<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Commenting\__fixtures__;

use Mockery\MockInterface;

class CreateMockFunctions
{
    private function withNoPhpDoc()
    {
    }


    /**
     * @param string $param
     */
    private function withNoReturnType(string $param)
    {
    }


    /**
     * @return MockedClass&MockInterface
     */
    private function correctlyOrdered()
    {
    }


    /**
     * @param string $param
     *
     * @return MockedClass&MockInterface
     */
    private function correctlyOrderedWithParam(string $param)
    {
    }


    /**
     * @return MockInterface&MockedClass
     */
    private function incorrectlyOrdered()
    {
    }


    /**
     * @param string $param
     *
     * @return MockInterface&MockedClass
     */
    private function incorrectlyOrderedWithParam(string $param)
    {
    }
}
