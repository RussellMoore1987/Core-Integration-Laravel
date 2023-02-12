<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\Exceptions\RequestMethodTypeValidatorFactoryException;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;
use Tests\TestCase;

class RequestMethodTypeValidatorFactoryTest extends TestCase
{
    protected $requestMethodTypeValidatorFactory;
    protected $classPath = 'App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators';

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestMethodTypeValidatorFactory = new RequestMethodTypeValidatorFactory();
    }

    public function test_RequestMethodTypeFactory_throws_exception_when_receiving_an_invalid_request_method_type()
    {
        $this->expectException(RequestMethodTypeValidatorFactoryException::class);
        $this->expectErrorMessage('RequestMethodTypeFactory: Invalid request method type "unlinked"');

        $this->requestMethodTypeValidatorFactory->getFactoryItem('Unlinked');
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
        return [
            'get' => ['get', "{$this->classPath}\GetRequestMethodTypeValidator"],
            'post' => ['post', "{$this->classPath}\PostRequestMethodTypeValidator"],
            'put' => ['put', "{$this->classPath}\PutRequestMethodTypeValidator"],
            'patch' => ['patch', "{$this->classPath}\PatchRequestMethodTypeValidator"],
            'delete' => ['delete', "{$this->classPath}\DeleteRequestMethodTypeValidator"],
        ];
    }
}
