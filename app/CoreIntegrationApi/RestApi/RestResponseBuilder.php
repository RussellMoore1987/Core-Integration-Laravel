<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\ResponseBuilder;

class RestResponseBuilder extends ResponseBuilder
{
    protected $validatedMetaData;
    protected $queryResult;

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
        return $this->makeRequest();
    }

    protected function makeRequest()
    {
        $httpMethodResponseBuilder = $this->httpMethodResponseBuilderFactory->getFactoryItem($this->validatedMetaData['endpointData']['httpMethod']);

        return $httpMethodResponseBuilder->buildResponse($this->validatedMetaData, $this->queryResult);
    }
}


// TODO: documentation builder
// TODO: move to separate class to ues in many places, formData, columnData, index, Get response - test it - set in validator
