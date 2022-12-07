<?php

namespace App\CoreIntegrationApi;

class ValidatorDataCollector 
{   
    public $availableResourceEndpoints;
    
    public $resource;
    public $resourceId;
    public $parameters = [];
    public $requestMethod;
    public $resourceObject;
    public $url;
    public $resourceInfo = [];
    public $endpointData = [];

    protected $rejectedParameters = [];
    protected $acceptedParameters = [];
    protected $queryArguments = [];

    public function setEndpointData(array $endpointData)
    {
        $this->endpointData = $endpointData;
    }
    public function getEndpointData()
    {
        return $this->endpointData;
    }

    public function setResourceInfo(array $resourceInfo)
    {
        $this->resourceInfo = $resourceInfo;
    }
    public function getResourceInfo()
    {
        return $this->resourceInfo;
    }

    public function setRejectedParameter(array $rejectedParameters)
    {
        $this->setArrayParameter('rejectedParameters', $rejectedParameters);
    }
    public function getRejectedParameters()
    {
        return $this->rejectedParameters;
    }
    
    public function setAcceptedParameter(array $acceptedParameters)
    {
        $this->setArrayParameter('acceptedParameters', $acceptedParameters);
    }
    public function getAcceptedParameters()
    {
        return $this->acceptedParameters;
    }

    public function setQueryArgument(array $queryArguments)
    {
        $this->setArrayParameter('queryArguments', $queryArguments);
    }
    public function getQueryArguments()
    {
        return $this->queryArguments;
    }

    protected function setArrayParameter($thisArrayString, $parameters)
    {
        foreach ($parameters as $columnName => $value) {
            $this->$thisArrayString[$columnName] = $value;
        }
    }

    public function getAllData()
    {
        return [
           'endpointData' => $this->endpointData,
           'resourceInfo' => $this->resourceInfo,
           'rejectedParameters' => $this->rejectedParameters,
           'acceptedParameters' => $this->acceptedParameters,
           'queryArguments' => $this->queryArguments,
        ];
    }

    public function reset()
    {
        $this->endpointData = [];
        $this->resourceInfo = [];
        $this->rejectedParameters = [];
        $this->acceptedParameters = [];
        $this->queryArguments = [];

        // test
        $this->resource = null;
        $this->resourceId = null;
        $this->parameters = null;
        $this->requestMethod = null;
        $this->url = null;
    }
}