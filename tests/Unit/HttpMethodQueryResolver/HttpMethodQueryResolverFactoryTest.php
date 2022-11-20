<?php

namespace Tests\Unit\HttpMethodQueryResolver;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolverFactory;
use Tests\TestCase;

class HttpMethodQueryResolverFactoryTest extends TestCase
{
    protected $classPath = 'App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers';

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpMethodQueryResolverFactory = new HttpMethodQueryResolverFactory();
    }

    /**
     * @dataProvider httpMethodProvider
     */
    public function test_creation_of_httpMethodQueryResolver_classes_via_HttpMethodQueryResolverFactory_class($httpMethod, $classPath)
    {
        $httpMethodQueryResolver = $this->httpMethodQueryResolverFactory->getFactoryItem($httpMethod);

        $this->assertInstanceOf($classPath, $httpMethodQueryResolver);
    }
    public function httpMethodProvider()
    {
        // TODO: variables for similar strings
        return [
            'get' => ['get', "{$this->classPath}\GetHttpMethodQueryResolver"],
            'post' => ['post', "{$this->classPath}\PostHttpMethodQueryResolver"],
            'put' => ['put', "{$this->classPath}\PutHttpMethodQueryResolver"],
            'patch' => ['patch', "{$this->classPath}\PatchHttpMethodQueryResolver"],
            'delete' => ['delete', "{$this->classPath}\DeleteHttpMethodQueryResolver"],
        ];
    }
}
