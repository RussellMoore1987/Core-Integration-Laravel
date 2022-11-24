<?php

namespace App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders\HttpMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

class PutHttpMethodResponseBuilder implements HttpMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        return response()->json([], 200);
    }
}