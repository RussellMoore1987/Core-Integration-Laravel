<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ResourceDataProvider;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\CoreIntegrationApi\ValidatorDataCollector;

class EndpointValidator
{
    function __construct(ResourceDataProvider $resourceDataProvider) 
    {
        $this->resourceDataProvider = $resourceDataProvider;
    }   

    
    public function validateEndPoint(ValidatorDataCollector &$validatorDataCollector)
    {


        $this->validatorDataCollector = $validatorDataCollector;
        // $this->resource = $this->validatorDataCollector->resource;
        // $this->resourceId = $this->validatorDataCollector->resourceId;
        // $this->parameters = $this->validatorDataCollector->parameters;
        // $this->requestMethod = $this->validatorDataCollector->requestMethod;
        // $this->url = $this->validatorDataCollector->url;






        if (array_key_exists($this->validatorDataCollector->resource, $this->validatorDataCollector->availableResourceEndpoints) ) {
            $this->setResourceVariables();
            $this->setEndpointDataInValidatorDataCollector();
        } elseif ($this->validatorDataCollector->resource != 'index') {
            $this->returnEndpointError();
        } 

        $this->setResourceInfo();
    }

    protected function setResourceVariables()
    {
        $this->validatorDataCollector->resourceObject = new $this->validatorDataCollector->availableResourceEndpoints[$this->validatorDataCollector->resource]();
        $this->resourceDataProvider->setResource($this->validatorDataCollector->resourceObject);
        $this->validatorDataCollector->resourceInfo = $this->resourceDataProvider->getResourceInfo();
    }

    protected function setEndpointDataInValidatorDataCollector()
    {
        $this->setEndpointData();
        $this->validatorDataCollector->setAcceptedParameter([
            "endpoint" => [
                'message' => "\"{$this->validatorDataCollector->resource}\" is a valid resource/endpoint for this API. You can also review available resources/endpoints at " . $this->getIndexUrl()
            ]
        ]);
    }

    protected function getIndexUrl()
    {
        return substr($this->validatorDataCollector->url, 0, strpos($this->validatorDataCollector->url, 'api/v1/') + 7);
    }

    protected function setEndpointData()
    {
        $this->validatorDataCollector->endpointData = [
            'resource' => $this->validatorDataCollector->resource, 
            'resourceId' => $this->validatorDataCollector->resourceId,  
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->validatorDataCollector->url,
            'requestMethod' => $this->validatorDataCollector->requestMethod, // TODO: name might need to change when we add in the context api accessMethodTypeValidatorFactor structure
        ]; 
        $this->checkForResourceId();
        // $this->validatorDataCollector->setEndpointData($this->endpointData);
    }

    protected function checkForResourceId()
    {
        if ($this->validatorDataCollector->resourceId) {
            $primaryKeyName = $this->validatorDataCollector->resourceInfo['primaryKeyName'];
            $this->validatorDataCollector->parameters[$primaryKeyName] = $this->validatorDataCollector->resourceId;
            $this->validatorDataCollector->endpointData['resourceIdConvertedTo'] = [$primaryKeyName => $this->validatorDataCollector->resourceId];
        }
    }

    protected function returnEndpointError()
    {
        $response = response()->json([
            'error' => 'Invalid Endpoint',
            'message' => "\"{$this->validatorDataCollector->resource}\" is not a valid resource/endpoint for this API. Please review available resources/endpoints at " . $this->getIndexUrl(),
            'status_code' => 400,
        ], 400);
        throw new HttpResponseException($response);
    }

    protected function setResourceInfo()
    {
        $this->validatorDataCollector->setResourceInfo($this->validatorDataCollector->resourceInfo);
    }
}