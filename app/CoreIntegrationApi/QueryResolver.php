<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolverFactory;
abstract class QueryResolver
{
    protected $requestMethodQueryResolverFactory;

    function __construct(RequestMethodQueryResolverFactory $requestMethodQueryResolverFactory) 
    {
        $this->requestMethodQueryResolverFactory = $requestMethodQueryResolverFactory;
    }

    abstract public function resolve($validatedMetaData);
}