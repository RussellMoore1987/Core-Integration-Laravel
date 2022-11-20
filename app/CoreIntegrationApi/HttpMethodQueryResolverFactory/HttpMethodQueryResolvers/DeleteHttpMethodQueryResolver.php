<?php

namespace App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\HttpMethodQueryResolver;

class DeleteHttpMethodQueryResolver implements HttpMethodQueryResolver
{
    public function resolveQuery($validatedMetaData) : array
    {
        return [];
    }
}