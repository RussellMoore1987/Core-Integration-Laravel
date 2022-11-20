<?php

namespace App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\HttpMethodQueryResolver;

class GetHttpMethodQueryResolver implements HttpMethodQueryResolver
{
    public function resolveQuery($validatedMetaData) : array
    {
        return [];
    }
}