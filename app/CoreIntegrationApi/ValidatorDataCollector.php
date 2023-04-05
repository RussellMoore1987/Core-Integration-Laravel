<?php

namespace App\CoreIntegrationApi;

class ValidatorDataCollector
{
    public $resource;
    public $resourceId;
    public $parameters = [];
    public $requestMethod;
    public $resourceObject;
    public $url; // TODO: may need to move this mostly applies to the rest API
    public $resourceInfo = [];
    public $endpointData = [];

    protected $rejectedParameters = [];
    protected $acceptedParameters = [];
    protected $queryArguments = [];

    public function setRejectedParameters(array $rejectedParameters): void
    {
        $this->setArrayParameter('rejectedParameters', $rejectedParameters);
    }
    public function getRejectedParameters(): array
    {
        return $this->rejectedParameters;
    }
    
    public function setAcceptedParameters(array $acceptedParameters): void
    {
        $this->setArrayParameter('acceptedParameters', $acceptedParameters);
    }
    public function getAcceptedParameters(): array
    {
        return $this->acceptedParameters;
    }

    public function setQueryArgument(array $queryArguments): void
    {
        $this->setArrayParameter('queryArguments', $queryArguments);
    }
    public function getQueryArguments(): array
    {
        return $this->queryArguments;
    }

    protected function setArrayParameter($thisArrayString, $parameters): void
    {
        foreach ($parameters as $columnName => $value) {
            $this->$thisArrayString[$columnName] = $value;
        }
    }

    public function getValidatedMetaData(): array
    {
        return [
           'endpointData' => $this->endpointData,
           'resourceInfo' => $this->resourceInfo,
           'rejectedParameters' => $this->rejectedParameters,
           'acceptedParameters' => $this->acceptedParameters,
           'queryArguments' => $this->queryArguments,
        ];
    }

    public function reset(): void // reset for reuse, context api
    {
        $this->resource = null;
        $this->resourceId = null;
        $this->parameters = [];
        $this->requestMethod = null;
        $this->resourceObject = null;
        $this->url = null;
        $this->resourceInfo = [];
        $this->endpointData = [];

        $this->rejectedParameters = [];
        $this->acceptedParameters = [];
        $this->queryArguments = [];
    }
}
