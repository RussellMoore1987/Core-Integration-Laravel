<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders;

use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

// TODO: PATCH =======================================
// Update existing record (just parts sent in)
// 200
// Resource record, old -> new
// Status
// What changed
// update validation

class PatchRequestMethodResponseBuilder implements RequestMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        return response()->json([], 200);
    }
}