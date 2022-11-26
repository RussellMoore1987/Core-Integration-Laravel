<?php

namespace App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders\HttpMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

// TODO: POST =======================================
// Record created
// 201 
// Resource record
// http Status
// location to find the new record

class PostHttpMethodResponseBuilder implements HttpMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        return response()->json([], 201);
    }
}