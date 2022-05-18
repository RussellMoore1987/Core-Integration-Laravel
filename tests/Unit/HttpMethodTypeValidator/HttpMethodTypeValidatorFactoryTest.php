<?php

namespace Tests\Unit\HttpMethodTypeValidator;

use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidatorFactory;
use Tests\TestCase;
class HttpMethodTypeValidatorFactoryTest extends TestCase
{
    protected $classPath = 'App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators';

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpMethodTypeValidatorFactory = new HttpMethodTypeValidatorFactory();
    }

    /**
     * @dataProvider httpMethodProvider
     */
    public function test_creation_of_httpMethodTypeValidator_classes_via_HttpMethodTypeValidatorFactory_class($httpMethod, $classPath)
    {
        $httpMethodTypeValidator = $this->httpMethodTypeValidatorFactory->getFactoryItem($httpMethod);

        $this->assertInstanceOf($classPath, $httpMethodTypeValidator);
    }
    public function httpMethodProvider()
    {
        // TODO: variables for similar strings
        return [
            'get' => ['get', "{$this->classPath}\GetHttpMethodTypeValidator"],
            'post' => ['post', "{$this->classPath}\PostHttpMethodTypeValidator"],
            'put' => ['put', "{$this->classPath}\PutHttpMethodTypeValidator"],
            'patch' => ['patch', "{$this->classPath}\PatchHttpMethodTypeValidator"],
            'delete' => ['delete', "{$this->classPath}\DeleteHttpMethodTypeValidator"],
        ];
    }
}
