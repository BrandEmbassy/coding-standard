<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs\Commenting\__fixtures__;

use Mockery\MockInterface;

class CreateMockFunctions
{
    private function createWithNoPhpDocMock()
    {
    }


    /**
     * @param string $param
     */
    private function createWithNoReturnTypeMock(string $param)
    {
    }


    /**
     * @return MockedClass&MockInterface
     */
    private function createCorrectlyOrderedMock()
    {
    }


    /**
     * @param string $param
     *
     * @return MockedClass&MockInterface
     */
    private function createCorrectlyOrderedWithParamMock(string $param)
    {
    }


    /**
     * @return MockInterface&MockedClass
     */
    private function createIncorrectlyOrderedMock()
    {
    }


    /**
     * @param string $param
     *
     * @return MockInterface&MockedClass
     */
    private function createIncorrectlyOrderedWithParamMock(string $param)
    {
    }
}
