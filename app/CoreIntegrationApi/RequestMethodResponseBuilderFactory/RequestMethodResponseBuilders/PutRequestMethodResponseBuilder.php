<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders;

use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

// TODO: PUT ==========================================
// Update existing record (all of it)
// 200
// Resource record, old -> new
// Status
// What changed???
// Creation validation

class PutRequestMethodResponseBuilder implements RequestMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        return response()->json([], 200);
    }
}