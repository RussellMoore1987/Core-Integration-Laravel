<?php

namespace App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers;

interface HttpMethodQueryResolver
{
    public function resolveQuery($validatedMetaData) : array;
}