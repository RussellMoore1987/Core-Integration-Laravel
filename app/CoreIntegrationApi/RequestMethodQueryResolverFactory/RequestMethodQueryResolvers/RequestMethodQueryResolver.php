<?php

namespace App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers;

interface RequestMethodQueryResolver
{
    public function resolveQuery($validatedMetaData);
}