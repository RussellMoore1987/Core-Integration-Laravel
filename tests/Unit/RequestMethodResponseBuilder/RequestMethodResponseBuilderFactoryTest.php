<?php

namespace Tests\Unit\RequestMethodResponseBuilder;

use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilderFactory;
use Tests\TestCase;

class RequestMethodResponseBuilderFactoryTest extends TestCase
{
    protected $classPath = 'App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders';

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestMethodResponseBuilderFactory = new RequestMethodResponseBuilderFactory();
    }

    /**
     * @dataProvider requestMethodProvider
     */
    public function test_creation_of_requestMethodResponseBuilder_classes_via_requestMethodResponseBuilderFactory_class($requestMethod, $classPath)
    {
        $requestMethodResponseBuilder = $this->requestMethodResponseBuilderFactory->getFactoryItem($requestMethod);

        $this->assertInstanceOf($classPath, $requestMethodResponseBuilder);
    }
    public function requestMethodProvider()
    {
        // TODO: variables for similar strings
        return [
            'get' => ['get', "{$this->classPath}\GetRequestMethodResponseBuilder"],
            'post' => ['post', "{$this->classPath}\PostRequestMethodResponseBuilder"],
            'put' => ['put', "{$this->classPath}\PutRequestMethodResponseBuilder"],
            'patch' => ['patch', "{$this->classPath}\PatchRequestMethodResponseBuilder"],
            'delete' => ['delete', "{$this->classPath}\DeleteRequestMethodResponseBuilder"],
        ];
    }
}
