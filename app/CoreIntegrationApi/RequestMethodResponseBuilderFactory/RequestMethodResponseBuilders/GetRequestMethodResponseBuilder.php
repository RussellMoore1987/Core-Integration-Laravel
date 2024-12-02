<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders;

use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;
use Illuminate\Http\Exceptions\HttpResponseException;
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
    protected $validatedMetaData;
    protected $queryResult;
    protected $response;

    public function buildResponse($validatedMetaData, $queryResult): JsonResponse
    {
        $this->validatedMetaData = $validatedMetaData;
        $this->queryResult = $queryResult;
        $this->makeGetRequest();
        return $this->response;
    }

    protected function makeGetRequest(): void
    {
        if (is_array($this->queryResult)) {
            // form data or column data
            $this->response = response()->json($this->queryResult, 200);
        } else {
            $paginateObj = json_decode($this->queryResult->toJson(), true);
            $paginateObj = $this->setGetResponse($paginateObj);

            $resourceId = $this->validatedMetaData['endpointData']['resourceId'];

            if ($this->isSingleIdRequest($resourceId)) {
                if (count($paginateObj['data']) == 0) {
                    $resource = $this->validatedMetaData['endpointData']['resource'];
                    $this->response = response()->json(['message' => "The record with the id of $resourceId at the \"$resource\" endpoint was not found"], 404);
                } else {
                    $this->response = response()->json($paginateObj['data'][0], 200);
                }
            } else {
                $this->isPagePramTooHigh($paginateObj);

                $this->response = response()->json($paginateObj, 200);
            }
        }
    }

    private function setGetResponse(array $paginateObj): array
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

    private function isPagePramTooHigh(array $paginateObj): void
    {
        $lastPage = $paginateObj['last_page'];
        $currentPage = $paginateObj['current_page'];
        if($currentPage > $lastPage) {
            $response = response()->json([
                'error' => 'Default page parameter is invalid',
                'message' => "The page parameter is too high for the current data set. The last page is {$lastPage} and you requested page {$currentPage}.",
                'statusCode' => 422,
            ], 422);
            throw new HttpResponseException($response);
        }
    }

    private function isSingleIdRequest($resourceId): bool // @IsSingleIdRequest
    {
        return $resourceId && !str_contains($resourceId, ',') && !str_contains($resourceId, '::');
    }
}
