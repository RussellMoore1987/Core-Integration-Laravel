<?php

namespace App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders\HttpMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

// TODO: PATCH =======================================
// Update existing record (just parts sent in)
// 200
// Resource record???
// Status
// What changed
// update validation

class PatchHttpMethodResponseBuilder implements HttpMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        return response()->json([], 200);
    }
}