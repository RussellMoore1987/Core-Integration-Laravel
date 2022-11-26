<?php

namespace App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders\HttpMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

class PostHttpMethodResponseBuilder implements HttpMethodResponseBuilder
{
    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        $resourcePrimaryKey = $queryResult->getKeyName(); // TODO: perhaps set higher up in class info
        $response = [
            'status' => 201,
            'newRecord' => $queryResult->toArray(),
            'newRecordLocation' => $validatedMetaData['endpointData']['url'] . '/' . $queryResult->$resourcePrimaryKey,
        ];
        return response()->json($response, 201);
    }
}