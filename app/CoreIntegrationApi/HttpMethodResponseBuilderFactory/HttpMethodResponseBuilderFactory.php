<?php

namespace App\CoreIntegrationApi\HttpMethodResponseBuilderFactory;

use App\CoreIntegrationApi\HttpMethodTypeFactory;
use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders\HttpMethodResponseBuilder;

class HttpMethodResponseBuilderFactory extends HttpMethodTypeFactory
{
    public function getFactoryItem($httpMethod) : HttpMethodResponseBuilder
    {
        $classPath = 'App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders';

        $this->factoryReturnArray = [
            'get' => "{$classPath}\GetHttpMethodResponseBuilder",
            'post' => "{$classPath}\PostHttpMethodResponseBuilder",
            'put' => "{$classPath}\PutHttpMethodResponseBuilder",
            'patch' => "{$classPath}\PatchHttpMethodResponseBuilder",
            'delete' => "{$classPath}\DeleteHttpMethodResponseBuilder",
        ];

        return parent::getFactoryItem($httpMethod);
    }
}