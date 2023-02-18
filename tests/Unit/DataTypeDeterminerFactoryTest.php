<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\Exceptions\DataTypeDeterminerFactoryException;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use Tests\TestCase;

class DataTypeDeterminerFactoryTest extends TestCase
{
    protected $parameterValidatorFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parameterValidatorFactory = new ParameterValidatorFactory();
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_DataTypeDeterminerFactory_throws_exception_when_dataType(): void
    {
        $this->expectException(DataTypeDeterminerFactoryException::class);
        $this->expectErrorMessage('The dataType of "none" is unsupported in the factoryItemArray, located in "App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory"');
        $this->parameterValidatorFactory->getFactoryItem('none');
    }
}
