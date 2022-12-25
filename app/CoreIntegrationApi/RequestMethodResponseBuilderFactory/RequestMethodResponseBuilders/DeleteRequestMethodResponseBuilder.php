<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders;

use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

// TODO: DELETE ======================================
// Delete 
// 200
// Status
// Record with id of deleted
// 404 for consecutive attempts

class DeleteRequestMethodResponseBuilder implements RequestMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        return response()->json([], 200);
    }
}