<?php

namespace App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\RequestMethodQueryResolver;

class PatchRequestMethodQueryResolver implements RequestMethodQueryResolver
{
    public function resolveQuery($validatedMetaData)
    {
        return [];
    }
}