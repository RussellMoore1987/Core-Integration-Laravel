<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory;

use App\CoreIntegrationApi\RequestMethodTypeFactory;
use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;

class RequestMethodResponseBuilderFactory extends RequestMethodTypeFactory
{
    public function getFactoryItem($requestMethod) : RequestMethodResponseBuilder
    {
        $classPath = 'App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders';

        $this->factoryReturnArray = [
            'get' => "{$classPath}\GetRequestMethodResponseBuilder",
            'post' => "{$classPath}\PostRequestMethodResponseBuilder",
            'put' => "{$classPath}\PutRequestMethodResponseBuilder",
            'patch' => "{$classPath}\PatchRequestMethodResponseBuilder",
            'delete' => "{$classPath}\DeleteRequestMethodResponseBuilder",
        ];

        return parent::getFactoryItem($requestMethod);
    }
}