<?php

namespace App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders\HttpMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

// TODO: DELETE ======================================
// Delete 
// 200
// Status
// Record with id of deleted
// 404 for consecutive attempts

class DeleteHttpMethodResponseBuilder implements HttpMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        return response()->json([], 200);
    }
}