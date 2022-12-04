<?php

namespace App\CoreIntegrationApi\RequestMethodQueryResolverFactory;

use App\CoreIntegrationApi\RequestMethodTypeFactory;
use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\RequestMethodQueryResolver;

class RequestMethodQueryResolverFactory extends RequestMethodTypeFactory
{
    public function getFactoryItem($requestMethod) : RequestMethodQueryResolver
    {
        $classPath = 'App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers';

        $this->factoryReturnArray = [
            'get' => "{$classPath}\GetRequestMethodQueryResolver",
            'post' => "{$classPath}\PostRequestMethodQueryResolver",
            'put' => "{$classPath}\PutRequestMethodQueryResolver",
            'patch' => "{$classPath}\PatchRequestMethodQueryResolver",
            'delete' => "{$classPath}\DeleteRequestMethodQueryResolver",
        ];

        return parent::getFactoryItem($requestMethod);
    }
}