<?php

namespace App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilders\HttpMethodResponseBuilder;
use Illuminate\Http\JsonResponse;

class GetHttpMethodResponseBuilder implements HttpMethodResponseBuilder
{
    private $validatedMetaData;
    private $response;

    public function buildResponse($validatedMetaData, $queryResult) : JsonResponse
    {
        $this->validatedMetaData = $validatedMetaData;
        $this->queryResult = $queryResult;
        $this->makeGetRequest();
        return $this->response;
    }

    protected function makeGetRequest()
    {
        if ($this->validatedMetaData['endpointData']['httpMethod'] == 'GET') {
            if (is_array($this->queryResult)) {
                // form data or column data
                $this->response = response()->json($this->queryResult, 200);
            } else {
                $paginateObj = json_decode($this->queryResult->toJson(), true);
                $paginateObj = $this->setGetResponse($paginateObj);
    
                $endpointId = $this->validatedMetaData['endpointData']['endpointId'];
    
                if ($endpointId && !str_contains($endpointId, ',')) {
                    if (count($paginateObj['data']) == 0) {
                        $endpoint = $this->validatedMetaData['endpointData']['endpoint'];
                        $this->response = response()->json(['message' => "The record with the id of $endpointId at the \"$endpoint\" endpoint was not found"], 404);
                    } else {
                        $this->response = response()->json($paginateObj['data'][0], 200);
                    }
                } else {
                    $this->response = response()->json($paginateObj, 200);
                }
            }
        }
    }

    protected function setGetResponse($paginateObj)
    {
        if (isset($this->validatedMetaData['extraData']['acceptableParameters'])) {
            foreach ($this->validatedMetaData['extraData']['acceptableParameters'] as $columnName => $columnArray) {
                $paginateObj['availableEndpointParameters']['parameters'][$columnName] = $columnArray['api_data_type'];
            }
            $paginateObj['availableEndpointParameters']['parameters']['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the apiDocumentation.parameterDataTypes section.', 
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters'] = [
                'columns' => 'endpoint parameters',
                'orderBy' => 'endpoint parameters',
                'methodCalls' => [
                    'value' => 'endpoint methods',
                    'availableMethodCalls' => $this->validatedMetaData['extraData']['availableMethodCalls'],
                ],
                'includes' => [
                    'value' => 'endpoint includes/relationships',
                    'availableIncludes' => $this->validatedMetaData['extraData']['availableIncludes'],
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
        unset($paginateObj['endpointData']['class']);

        return $paginateObj;
    }
}