<?php

namespace Tests\Unit\HttpMethodResponseBuilder;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilderFactory;
use Tests\TestCase;

class HttpMethodResponseBuilderFactoryTest extends TestCase
{
    protected $classPath = 'App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders';

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpMethodResponseBuilderFactory = new HttpMethodResponseBuilderFactory();
    }

    /**
     * @dataProvider httpMethodProvider
     */
    public function test_creation_of_httpMethodResponseBuilder_classes_via_HttpMethodResponseBuilderFactory_class($httpMethod, $classPath)
    {
        $httpMethodResponseBuilder = $this->httpMethodResponseBuilderFactory->getFactoryItem($httpMethod);

        $this->assertInstanceOf($classPath, $httpMethodResponseBuilder);
    }
    public function httpMethodProvider()
    {
        // TODO: variables for similar strings
        return [
            'get' => ['get', "{$this->classPath}\GetHttpMethodResponseBuilder"],
            'post' => ['post', "{$this->classPath}\PostHttpMethodResponseBuilder"],
            'put' => ['put', "{$this->classPath}\PutHttpMethodResponseBuilder"],
            'patch' => ['patch', "{$this->classPath}\PatchHttpMethodResponseBuilder"],
            'delete' => ['delete', "{$this->classPath}\DeleteHttpMethodResponseBuilder"],
        ];
    }
}
