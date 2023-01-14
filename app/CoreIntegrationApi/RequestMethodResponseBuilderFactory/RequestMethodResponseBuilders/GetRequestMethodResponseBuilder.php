<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders;

use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

// TODO: GET =======================================
// Bad request
// 400 (GET request itself is not correctly formed)

// TODO: add ability to ask for methodcalls and includes, like columndata
// TODO: just data
// TODO: just pagination data
// TODO: form data with data types
// TODO: add default Parameters
// TODO: resourceData add not default, pagination is default 

class GetRequestMethodResponseBuilder implements RequestMethodResponseBuilder
{
    private $validatedMetaData;
    private $response;

    public function buildResponse($validatedMetaData, $queryResult): JsonResponse
    {
        $this->validatedMetaData = $validatedMetaData;
        $this->queryResult = $queryResult;
        $this->makeGetRequest();
        return $this->response;
    }

    protected function makeGetRequest()
    {
        if (is_array($this->queryResult)) {
            // form data or column data
            $this->response = response()->json($this->queryResult, 200);
        } else {
            $paginateObj = json_decode($this->queryResult->toJson(), true);
            $paginateObj = $this->setGetResponse($paginateObj);

            $resourceId = $this->validatedMetaData['endpointData']['resourceId'];

            if ($resourceId && !str_contains($resourceId, ',')) {
                if (count($paginateObj['data']) == 0) {
                    $resource = $this->validatedMetaData['endpointData']['resource'];
                    $this->response = response()->json(['message' => "The record with the id of $resourceId at the \"$resource\" endpoint was not found"], 404);
                } else {
                    $this->response = response()->json($paginateObj['data'][0], 200);
                }
            } else {
                $this->response = response()->json($paginateObj, 200);
            }
        }
    }

    protected function setGetResponse($paginateObj)
    {
        if (isset($this->validatedMetaData['resourceInfo']['acceptableParameters'])) {
            foreach ($this->validatedMetaData['resourceInfo']['acceptableParameters'] as $columnName => $columnArray) {
                $paginateObj['availableResourceParameters']['parameters'][$columnName] = $columnArray['apiDataType'];
            }
            $paginateObj['availableResourceParameters']['parameters']['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the apiDocumentation.parameterDataTypes section.', 
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
            $paginateObj['availableResourceParameters']['defaultParameters'] = [
                'columns' => 'resource parameters',
                'orderBy' => 'resource parameters',
                'methodCalls' => [
                    'value' => 'resource methods',
                    'availableMethodCalls' => $this->validatedMetaData['resourceInfo']['availableMethodCalls'],
                ],
                'includes' => [
                    'value' => 'resource includes/relationships',
                    'availableIncludes' => $this->validatedMetaData['resourceInfo']['availableIncludes'],
                ],
                'page' => 'int',
                'perPage' => 'int',
                'columnData' => true,
                'formData' => true,
                'includeData' => true,
                'methodCallData' => true,
                'info' => [
                    'message' => 'Documentation on how to utilize default parameter data types can be found in the index response, in the apiDocumentation.defaultParameterDataTypes section.', 
                    'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
                ]
            ];
            
        }

        $paginateObj['rejectedParameters'] = $this->validatedMetaData['rejectedParameters'];
        $paginateObj['acceptedParameters'] = $this->validatedMetaData['acceptedParameters'];
        $paginateObj['endpointData'] = $this->validatedMetaData['endpointData'];

        return $paginateObj;
    }
}