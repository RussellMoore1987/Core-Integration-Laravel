<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolverFactory;
abstract class QueryResolver
{
    protected $httpMethodQueryResolverFactory;

    function __construct(HttpMethodQueryResolverFactory $httpMethodQueryResolverFactory) 
    {
        $this->httpMethodQueryResolverFactory = $httpMethodQueryResolverFactory;
    }

    abstract public function resolve($validatedMetaData);
}