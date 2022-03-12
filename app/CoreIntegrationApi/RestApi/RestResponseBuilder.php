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
            $paginateObj['availableEndpointParameters']['parameters']['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the ApiDocumentation section.', 
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
            // ! start here and test them
            // TODO: move to separate class to ues in many places, formData, columnData, index, Get response - test it
            $paginateObj['availableEndpointParameters']['defaultParameters']['columns'] = [
                'parameterNameOptions' => ['columns','select'],
                'info' => [
                    'description' => '"columns" is used to select data attributes/columns from an endpoint.', 
                    'usesDescription' => 'Example .../projects/?columns=id,title,roles,client,budget will return only the data attributes of id, title, roles, client and budget.',
                    'exampleResponse' => [
                        'id' => 34,
                        'title' => 'Laudantium Nesciunt Est Molestiae',
                        'roles' => 'Backend Developer',
                        'client' => 'Schmitt, Gerhold and Lemke',
                        'budget' => '0.00'
                    ],
                ]
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['orderby'] = [
                'parameterNameOptions' => ['orderby','order_by'],
                'info' => '"orderby" is used to order the return data for an end point. All parameter in the availableEndpointParameters.parameters for a given endpoint are available for sorting, ascending/ASC and descending/DESC.'
            ];
            // TODO: add ascending and descending
            $paginateObj['availableEndpointParameters']['defaultParameters']['methodcalls'] = [
                'parameterNameOptions' => ['methodcalls','method_calls'],
                'info' => '"methodcalls" allows you to access specific method calls from this given endpoint. Only the method calls below are available for this endpoints.',
                // TODO: add MethodCalls
                'availableMethodCalls' => []
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['includes'] = '';
            $paginateObj['availableEndpointParameters']['defaultParameters']['perpage'] = [
                'parameterNameOptions' => ['perpage','per_page'],
                'info' => '"perpage" is used to set the number of records returned this end point, the default is 50.'
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['page'] = '"page" is used to set the page number or offset of records returned to this end point. For example if you had 50 records per page and set the page parameter to 2 you would receive records from 51 to 100.';
            $paginateObj['availableEndpointParameters']['defaultParameters']['columndata'] = [
                'parameterNameOptions' => ['columndata','column_data'],
                'info' => '"columndata" is used as a reference tool to know how to utilize this endpoint\'s parameters. Setting this parameter will activate the column data being returned. "columndata" doesn\'t care about the value set, for example columndata=yes and columndata=no returns the same response.'
            ];
            $paginateObj['availableEndpointParameters']['defaultParameters']['formdata'] = [
                'parameterNameOptions' => ['formdata','form_data'],
                'info' => '"formdata" is used as a reference tool to know how to utilize this endpoint\'s parameters for form creation. Setting this parameter will activate the form data being returned. "formdata" doesn\'t care about the value set, for example formdata=yes and formdata=no returns the same response.'
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