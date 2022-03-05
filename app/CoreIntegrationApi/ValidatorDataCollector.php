<?php

namespace App\CoreIntegrationApi;

class ValidatorDataCollector 
{   
    protected $endpointData = [];
    protected $extraData = [];
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

    public function setExtraData(array $extraData)
    {
        $this->extraData = $extraData;
    }
    public function getExtraData()
    {
        return $this->extraData;
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
           'extraData' => $this->extraData,
           'rejectedParameters' => $this->rejectedParameters,
           'acceptedParameters' => $this->acceptedParameters,
           'queryArguments' => $this->queryArguments,
        ];
    }

    public function reset()
    {
        $this->endpointData = [];
        $this->extraData = [];
        $this->rejectedParameters = [];
        $this->acceptedParameters = [];
        $this->queryArguments = [];
    }
}