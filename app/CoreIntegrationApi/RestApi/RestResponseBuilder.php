<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\ResponseBuilder;

class RestResponseBuilder implements ResponseBuilder
{
    protected $validatedMetaData;
    protected $queryResult;
    protected $response;

    public function setValidatedMetaData($validatedMetaData)
    {
        $this->validatedMetaData = $validatedMetaData;
    }
    
    public function setResponseData($queryResult)
    {
        $this->queryResult = $queryResult;
    }

    public function make()
    {
        // dd($this->queryResult, $this->validatedMetaData, 'got here!!!');
        
        return $this->makeRequest();
    }

    protected function makeRequest()
    {
        $this->checkForEndpointError();
        $this->checkGetRequest();

        // response()->json($this->queryResult, 200);
        return $this->response;
    }

    protected function checkForEndpointError()
    {
        if ($this->validatedMetaData['endpointData']['endpointError']) {
            $this->response = response()->json($this->validatedMetaData['rejectedParameters'], 404);
        }
    }

    protected function checkGetRequest()
    {
        if (!$this->response && $this->validatedMetaData['endpointData']['httpMethod'] == 'GET') {
            $paginateObj = json_decode($this->queryResult->toJson(), true);
            $paginateObj = $this->setGetResponse($paginateObj);

            $endpointId = $this->validatedMetaData['endpointData']['endpointId'];

            if ($endpointId && !str_contains($endpointId, ',')) {
                if (count($paginateObj['data']) == 0) {
                    $endpoint = $this->validatedMetaData['endpointData']['endpoint'];
                    // ! start here **************************************************************
                    $this->response = response()->json(['message' => "The record with the id of $endpointId at the \"$endpoint\" endpoint was not found"], 404);
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
        if (isset($this->validatedMetaData['extraData']['acceptableParameters'])) {
            foreach ($this->validatedMetaData['extraData']['acceptableParameters'] as $columnName => $columnArray) {
                $paginateObj['availableEndpointParameters']['parameters'][$columnName] = $columnArray['api_data_type'];
            }
            $paginateObj['availableEndpointParameters']['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the ApiDocumentation section.', 
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
        }

        $paginateObj['rejectedParameters'] = $this->validatedMetaData['rejectedParameters'];
        $paginateObj['acceptedParameters'] = $this->validatedMetaData['acceptedParameters'];
        $paginateObj['endpointData'] = $this->validatedMetaData['endpointData'];
        unset($paginateObj['endpointData']['class']);

        return $paginateObj;
    }
}