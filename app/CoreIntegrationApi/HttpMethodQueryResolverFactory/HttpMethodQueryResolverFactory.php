<?php

namespace App\CoreIntegrationApi\HttpMethodQueryResolverFactory;

use App\CoreIntegrationApi\HttpMethodTypeFactory;
use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\HttpMethodQueryResolver;

class HttpMethodQueryResolverFactory extends HttpMethodTypeFactory
{
    public function getFactoryItem($httpMethod) : HttpMethodQueryResolver
    {
        $classPath = 'App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers';

        $this->factoryReturnArray = [
            'get' => "{$classPath}\GetHttpMethodQueryResolver",
            'post' => "{$classPath}\PostHttpMethodQueryResolver",
            'put' => "{$classPath}\PutHttpMethodQueryResolver",
            'patch' => "{$classPath}\PatchHttpMethodQueryResolver",
            'delete' => "{$classPath}\DeleteHttpMethodQueryResolver",
        ];

        return parent::getFactoryItem($httpMethod);
    }
}