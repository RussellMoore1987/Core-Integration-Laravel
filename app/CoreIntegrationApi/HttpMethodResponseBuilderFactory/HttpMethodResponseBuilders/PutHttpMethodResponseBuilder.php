<?php

namespace App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders\HttpMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

// TODO: PUT ==========================================
// Update existing record (all of it)
// 200
// Resource record, old -> new
// Status
// What changed???
// Creation validation

class PutHttpMethodResponseBuilder implements HttpMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        return response()->json([], 200);
    }
}