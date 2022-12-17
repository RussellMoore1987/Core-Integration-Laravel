<?php

namespace App\CoreIntegrationApi;

// ! start here ********************************************************* readability

class ValidatorDataCollector
{
    protected $availableResourceEndpoints;
    
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

    // TODO: test
    public function setAvailableResourceEndpoints(array $availableResourceEndpoints) : void
    {
        $this->availableResourceEndpoints = $availableResourceEndpoints;
    }
    public function getAvailableResourceEndpoints() : array
    {
        return $this->availableResourceEndpoints;
    }

    public function setRejectedParameter(array $rejectedParameters) : void // TODO: should this method be Plural
    {
        $this->setArrayParameter('rejectedParameters', $rejectedParameters);
    }
    public function getRejectedParameters() : array
    {
        return $this->rejectedParameters;
    }
    
    public function setAcceptedParameter(array $acceptedParameters) : void // TODO: should this method be Plural
    {
        $this->setArrayParameter('acceptedParameters', $acceptedParameters);
    }
    public function getAcceptedParameters() : array
    {
        return $this->acceptedParameters;
    }

    public function setQueryArgument(array $queryArguments) : void
    {
        $this->setArrayParameter('queryArguments', $queryArguments);
    }
    public function getQueryArguments() : array
    {
        return $this->queryArguments;
    }

    protected function setArrayParameter($thisArrayString, $parameters) : void
    {
        foreach ($parameters as $columnName => $value) {
            $this->$thisArrayString[$columnName] = $value;
        }
    }

    public function getValidatedMetaData() : array
    {
        return [
           'endpointData' => $this->endpointData,
           'resourceInfo' => $this->resourceInfo,
           'rejectedParameters' => $this->rejectedParameters,
           'acceptedParameters' => $this->acceptedParameters,
           'queryArguments' => $this->queryArguments,
        ];
    }

    public function reset() : void // reset for reuse, context api
    {
        $this->endpointData = [];
        $this->resourceInfo = [];
        $this->rejectedParameters = [];
        $this->acceptedParameters = [];
        $this->queryArguments = [];

        // TODO: test
        $this->resource = null;
        $this->resourceId = null;
        $this->parameters = null;
        $this->requestMethod = null;
        $this->url = null;
    }
}