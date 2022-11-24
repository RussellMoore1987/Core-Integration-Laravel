<?php

namespace App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\HttpMethodQueryResolver;
use App\CoreIntegrationApi\QueryAssembler;
use App\CoreIntegrationApi\QueryIndex;

class GetHttpMethodQueryResolver implements HttpMethodQueryResolver
{
    protected $queryAssembler;
    protected $queryIndex;
    protected $validatedMetaData;
    protected $queryResult;

    function __construct(QueryAssembler $queryAssembler, QueryIndex $queryIndex) 
    {
        $this->queryAssembler = $queryAssembler;
        $this->queryIndex = $queryIndex;
    }

    public function resolveQuery($validatedMetaData)
    {
        $this->validatedMetaData = $validatedMetaData;

        $this->checkEndpointColumnData();
        $this->checkIndex(); 
        $this->checkFormData();
        $this->checkGetRequest();

        return $this->queryResult; // TODO: test if casting to an array brakes anything, if not make array the correct return
    }

    protected function checkEndpointColumnData()
    {
        if (isset($this->validatedMetaData['acceptedParameters']['columnData'])) {
            foreach ($this->validatedMetaData['extraData']['acceptableParameters'] as $columnName => $columnArray) {
                $this->queryResult['availableEndpointParameters'][$columnName] = $columnArray['api_data_type'];
            }
            $this->queryResult['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the ApiDocumentation section.', 
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
        }
    }

    protected function checkIndex()
    {
        if (!$this->queryResult && $this->validatedMetaData['endpointData']['endpoint'] == 'index') {
            $this->queryResult = $this->queryIndex->get();   
        }
    }

    protected function checkFormData()
    {
        if (!$this->queryResult && isset($this->validatedMetaData['acceptedParameters']['formData'])) {
            // TODO: get form data
            $this->queryResult = 'form data';   
        }
    }

    protected function checkGetRequest()
    {
        if (!$this->queryResult && strtolower($this->validatedMetaData['endpointData']['httpMethod']) == 'get') {
            $this->queryResult = $this->queryAssembler->query($this->validatedMetaData);
        }
    }
}