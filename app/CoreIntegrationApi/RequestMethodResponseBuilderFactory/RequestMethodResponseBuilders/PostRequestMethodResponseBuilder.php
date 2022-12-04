<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders;

use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

class PostRequestMethodResponseBuilder implements RequestMethodResponseBuilder
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