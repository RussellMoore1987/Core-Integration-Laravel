<?php

namespace App\CoreIntegrationApi\RequestMethodQueryResolverFactory;

use App\CoreIntegrationApi\RequestMethodTypeFactory;
use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\RequestMethodQueryResolver;
use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\GetRequestMethodQueryResolver;
use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\PostRequestMethodQueryResolver;
use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\PutRequestMethodQueryResolver;
use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\PatchRequestMethodQueryResolver;
use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\DeleteRequestMethodQueryResolver;

class RequestMethodQueryResolverFactory extends RequestMethodTypeFactory
{
    protected $factoryReturnArray = [
        'get' => GetRequestMethodQueryResolver::class,
        'post' => PostRequestMethodQueryResolver::class,
        'put' => PutRequestMethodQueryResolver::class,
        'patch' => PatchRequestMethodQueryResolver::class,
        'delete' => DeleteRequestMethodQueryResolver::class,
    ];

    public function getFactoryItem($requestMethod): RequestMethodQueryResolver
    {
        return parent::getFactoryItem($requestMethod);
    }
}