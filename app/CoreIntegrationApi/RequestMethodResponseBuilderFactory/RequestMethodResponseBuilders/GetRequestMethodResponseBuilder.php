<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders;

use App\CoreIntegrationApi\FunctionalityProviders\Helper;
use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

// TODO: test this class
// TODO: GET =======================================
// Bad request
// 400 (GET request itself is not correctly formed)

// TODO: add ability to ask for methodcalls and includes, like columndata

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
            $paginateArray = json_decode($this->queryResult->toJson(), true);
            $paginateArray = $this->setGetResponse($paginateArray);

            $resourceId = $this->validatedMetaData['endpointData']['resourceId'];

            if (Helper::isSingleRestIdRequest($resourceId)) {
                if (count($paginateArray['data']) == 0) {
                    $resource = $this->validatedMetaData['endpointData']['resource'];
                    if (count($this->validatedMetaData['acceptedParameters']) > 2) { // 2 = endpoint and id prams
                        $this->getResponseIdAndCriteria($resourceId, $resource);
                    } else {
                        $this->response = response()->json(['message' => "The record with the id of $resourceId at the \"$resource\" endpoint was not found"], 404);
                    }
                } else {
                    $this->response = response()->json($paginateArray['data'][0], 200);
                }
            } else {
                $this->isPagePramTooHigh($paginateArray);

                $paginateArray = $this->adjustResponse($paginateArray);

                $this->response = response()->json($paginateArray, 200);
            }
        }
    }

    private function getResponseIdAndCriteria(int $resourceId, string $resource): void
    {
        $acceptedParameters = $this->validatedMetaData['acceptedParameters'];
        $ignoredParameters = [];
        foreach ($acceptedParameters as $key => $value) {
            if ($key == 'page' || $key == 'perPage') {
                $ignoredParameters[$key] = $value;
                unset($acceptedParameters[$key]);
            }
        }

        $this->response = response()->json([
            'message' => "The record with the id of $resourceId and the criteria provided for the \"$resource\" endpoint yielded no results",
            'acceptedParameters' => $acceptedParameters,
            'ignoredParameters' => $ignoredParameters,
        ], 404);
    }

    private function setGetResponse(array $paginateArray): array
    {
        if (isset($this->validatedMetaData['resourceInfo']['acceptableParameters'])) {
            foreach ($this->validatedMetaData['resourceInfo']['acceptableParameters'] as $columnName => $columnArray) {
                $paginateArray['availableResourceParameters']['parameters'][$columnName] = $columnArray['apiDataType'];
            }
            $paginateArray['availableResourceParameters']['parameters']['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the apiDocumentation.parameterDataTypes section.',
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
            $paginateArray['availableResourceParameters']['defaultParameters'] = [
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
                'dataOnly' => true,
                'fullInfo' => true,
                'includeData' => true,
                'methodCallData' => true,
                'info' => [
                    'message' => 'Documentation on how to utilize default parameter data types can be found in the index response, in the apiDocumentation.defaultParameterDataTypes section.',
                    'index_url' => $this->validatedMetaData['endpointData']['indexUrl'],
                ]
            ];
        }

        $paginateArray['rejectedParameters'] = $this->validatedMetaData['rejectedParameters'];
        $paginateArray['acceptedParameters'] = $this->validatedMetaData['acceptedParameters'];
        $paginateArray['endpointData'] = $this->validatedMetaData['endpointData'];

        return $paginateArray;
    }

    private function isPagePramTooHigh(array $paginateArray): void
    {
        $lastPage = $paginateArray['last_page'];
        $currentPage = $paginateArray['current_page'];
        if($currentPage > $lastPage) {
            $response = response()->json([
                'error' => 'Default page parameter is invalid',
                'message' => "The page parameter is too high for the current data set. The last page is {$lastPage} and you requested page {$currentPage}.",
                'statusCode' => 422,
            ], 422);
            throw new HttpResponseException($response);
        }
    }

    private function adjustResponse(array $paginateArray): array
    {
        $requestStructure = $this->validatedMetaData['endpointData']['defaultReturnRequestStructure'];

        if (isset($this->validatedMetaData['acceptedParameters']['dataOnly'])) {
            $requestStructure = 'dataOnly';
        } elseif (isset($this->validatedMetaData['acceptedParameters']['fullInfo'])) {
            $requestStructure = 'fullInfo';
        }

        if ($requestStructure == 'dataOnly') {
            $paginateArray = $paginateArray['data'];
        }
        
        return $paginateArray;
    }
}
