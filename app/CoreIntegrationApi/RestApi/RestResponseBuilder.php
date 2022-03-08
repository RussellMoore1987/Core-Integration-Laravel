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
        // dd($this->validatedMetaData['endpointData']);
        if (!$this->response && $this->validatedMetaData['endpointData']['httpMethod'] == 'GET') {
            $endpointId = $this->validatedMetaData['endpointData']['endpointId'];
            if ($endpointId && !str_contains(',', $endpointId)) {
                $paginateObj = json_decode($this->queryResult->toJson(), true);
                $this->response = response()->json($paginateObj['data'][0], 200);
            } else {
                $this->response = response()->json($this->queryResult, 200);
            }
        }
    }
}