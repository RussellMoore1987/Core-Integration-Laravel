<?php

namespace App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\RequestMethodQueryResolver;

class DeleteRequestMethodQueryResolver implements RequestMethodQueryResolver
{
    public function resolveQuery($validatedMetaData)
    {
        return [];
    }
}