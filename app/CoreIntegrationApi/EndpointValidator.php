<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ResourceDataProvider;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\CoreIntegrationApi\ValidatorDataCollector;

class EndpointValidator
{
    protected $resourceDataProvider;
    protected $validatorDataCollector;
    protected $availableResourceEndpoints;

    public function __construct(ResourceDataProvider $resourceDataProvider)
    {
        $this->resourceDataProvider = $resourceDataProvider;
    }

    public function validateEndPoint(ValidatorDataCollector &$validatorDataCollector) : void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->availableResourceEndpoints = $this->validatorDataCollector->getAvailableResourceEndpoints();
        
        if (array_key_exists($this->validatorDataCollector->resource, $this->availableResourceEndpoints)) {
            $this->setResourceVariables();
            $this->setEndpointDataInValidatorDataCollector();
        } elseif ($this->validatorDataCollector->resource != 'index') {
            $this->returnEndpointError();
        }
    }

    protected function setResourceVariables() : void
    {
        $this->validatorDataCollector->resourceObject = new $this->availableResourceEndpoints[$this->validatorDataCollector->resource]();
        $this->resourceDataProvider->setResource($this->validatorDataCollector->resourceObject);
        $this->validatorDataCollector->resourceInfo = $this->resourceDataProvider->getResourceInfo();
    }

    protected function setEndpointDataInValidatorDataCollector() : void
    {
        $this->setEndpointData();
        $this->validatorDataCollector->setAcceptedParameter([
            "endpoint" => [
                'message' => "\"{$this->validatorDataCollector->resource}\" is a valid resource/endpoint for this API. You can also review available resources/endpoints at " . $this->getIndexUrl()
            ]
        ]);
    }

    protected function getIndexUrl() : string
    {
        return substr($this->validatorDataCollector->url, 0, strpos($this->validatorDataCollector->url, 'api/v1/') + 7);
    }

    protected function setEndpointData() : void
    {
        $this->validatorDataCollector->endpointData = [
            'resource' => $this->validatorDataCollector->resource,
            'resourceId' => $this->validatorDataCollector->resourceId,
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->validatorDataCollector->url,
            'requestMethod' => $this->validatorDataCollector->requestMethod,
        ];
        $this->checkForResourceId();
    }

    protected function checkForResourceId() : void
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
            'error' => 'Resource/Endpoint Not Found',
            'message' => "\"{$this->validatorDataCollector->resource}\" is not a valid resource/endpoint for this API. Please review available resources/endpoints at " . $this->getIndexUrl(),
            'status_code' => 404,
        ], 404);
        throw new HttpResponseException($response);
    }
}