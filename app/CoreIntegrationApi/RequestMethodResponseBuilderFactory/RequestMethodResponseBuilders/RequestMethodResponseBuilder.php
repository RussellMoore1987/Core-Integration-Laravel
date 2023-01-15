<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders;

use Illuminate\Http\JsonResponse;

interface RequestMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult): JsonResponse;
}