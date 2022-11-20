<?php

namespace App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\HttpMethodQueryResolver;

class PostHttpMethodQueryResolver implements HttpMethodQueryResolver
{
    public function resolveQuery($validatedMetaData) : array
    {
        return [];
    }
}