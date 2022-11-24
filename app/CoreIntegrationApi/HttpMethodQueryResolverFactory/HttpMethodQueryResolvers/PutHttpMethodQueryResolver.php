<?php

namespace App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\HttpMethodQueryResolver;

class PutHttpMethodQueryResolver implements HttpMethodQueryResolver
{
    public function resolveQuery($validatedMetaData)
    {
        return [];
    }
}