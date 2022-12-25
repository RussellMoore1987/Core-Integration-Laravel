<?php

namespace App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\RequestMethodQueryResolver;

class PutRequestMethodQueryResolver implements RequestMethodQueryResolver
{
    public function resolveQuery($validatedMetaData)
    {
        return [];
    }
}