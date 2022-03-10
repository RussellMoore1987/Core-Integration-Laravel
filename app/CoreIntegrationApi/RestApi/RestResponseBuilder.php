<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\ResponseBuilder;
use Hamcrest\Arrays\IsArray;

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
    }

    protected function setGetResponse($paginateObj)
    {
        if (isset($this->validatedMetaData['extraData']['acceptableParameters'])) {
            foreach ($this->validatedMetaData['extraData']['acceptableParameters'] as $columnName => $columnArray) {
                $paginateObj['availableEndpointParameters']['parameters'][$columnName] = $columnArray['api_data_type'];
            }
            // TODO: move in with availableEndpointParameters.parameters
            $paginateObj['availableEndpointParameters']['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the ApiDocumentation section.', 
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
            // ! start here and test them
            $paginateObj['availableEndpointParameters']['defaultParameters']['columns'] = [
                'parameterNameOptions' => ['columns','select'],
                'info' => '"columns" is used to select data attributes/data columns from an endpoint.'
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['orderby'] = [
                'parameterNameOptions' => ['orderby','order_by'],
                'info' => '"orderby" is used to order return data for this end point. All parameter in the availableEndpointParameters.parameters are available for sorting ascending (ASC) and descending (DESC).'
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['methodcalls'] = [
                'parameterNameOptions' => ['methodcalls','method_calls'],
                'info' => '...'
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['includes'] = '';
            $paginateObj['availableEndpointParameters']['defaultParameters']['perpage'] = [
                'parameterNameOptions' => ['perpage','per_page'],
                'info' => '...'
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['page'] = '';
            $paginateObj['availableEndpointParameters']['defaultParameters']['columndata'] = [
                'parameterNameOptions' => ['columndata','column_data'],
                'info' => '...'
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['formdata'] = [
                'parameterNameOptions' => ['formdata','form_data'],
                'info' => '...'
            ];
        }
        // TODO: add default Parameters

        $paginateObj['rejectedParameters'] = $this->validatedMetaData['rejectedParameters'];
        $paginateObj['acceptedParameters'] = $this->validatedMetaData['acceptedParameters'];
        $paginateObj['endpointData'] = $this->validatedMetaData['endpointData'];
        unset($paginateObj['endpointData']['class']);

        return $paginateObj;
    }
}