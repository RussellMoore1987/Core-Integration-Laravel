<?php

namespace Tests\Unit\RequestMethodQueryResolver;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolverFactory;
use Tests\TestCase;

class RequestMethodQueryResolverFactoryTest extends TestCase
{
    protected $classPath = 'App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers';

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestMethodQueryResolverFactory = new RequestMethodQueryResolverFactory();
    }

    /**
     * @dataProvider requestMethodProvider
     */
    public function test_creation_of_requestMethodQueryResolver_classes_via_requestMethodQueryResolverFactory_class($requestMethod, $classPath)
    {
        $requestMethodQueryResolver = $this->requestMethodQueryResolverFactory->getFactoryItem($requestMethod);

        $this->assertInstanceOf($classPath, $requestMethodQueryResolver);
    }
    public function requestMethodProvider()
    {
        // TODO: variables for similar strings
        return [
            'get' => ['get', "{$this->classPath}\GetRequestMethodQueryResolver"],
            'post' => ['post', "{$this->classPath}\PostRequestMethodQueryResolver"],
            'put' => ['put', "{$this->classPath}\PutRequestMethodQueryResolver"],
            'patch' => ['patch', "{$this->classPath}\PatchRequestMethodQueryResolver"],
            'delete' => ['delete', "{$this->classPath}\DeleteRequestMethodQueryResolver"],
        ];
    }
}
