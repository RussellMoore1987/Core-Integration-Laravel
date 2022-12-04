<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;
use Tests\TestCase;

class RequestMethodTypeValidatorFactoryTest extends TestCase
{
    protected $classPath = 'App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators';

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestMethodTypeValidatorFactory = new RequestMethodTypeValidatorFactory();
    }

    /**
     * @dataProvider requestMethodProvider
     */
    public function test_creation_of_RequestMethodTypeValidator_classes_via_RequestMethodTypeValidatorFactory_class($requestMethod, $classPath)
    {
        $requestMethodTypeValidator = $this->requestMethodTypeValidatorFactory->getFactoryItem($requestMethod);

        $this->assertInstanceOf($classPath, $requestMethodTypeValidator);
    }
    public function requestMethodProvider()
    {
        // TODO: variables for similar strings
        return [
            'get' => ['get', "{$this->classPath}\GetRequestMethodTypeValidator"],
            'post' => ['post', "{$this->classPath}\PostRequestMethodTypeValidator"],
            'put' => ['put', "{$this->classPath}\PutRequestMethodTypeValidator"],
            'patch' => ['patch', "{$this->classPath}\PatchRequestMethodTypeValidator"],
            'delete' => ['delete', "{$this->classPath}\DeleteRequestMethodTypeValidator"],
        ];
    }
}
