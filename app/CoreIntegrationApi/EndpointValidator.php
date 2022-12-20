<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ResourceInfoProvider;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\CoreIntegrationApi\ValidatorDataCollector;

class EndpointValidator
{
    protected $resourceInfoProvider;
    protected $validatorDataCollector;
    protected $availableResourceEndpoints;

    public function __construct(ResourceInfoProvider $resourceInfoProvider)
    {
        $this->resourceInfoProvider = $resourceInfoProvider; // TODO: name change ??? resourceInfoProvider info -> about the resource, both are fairly ambiguous
    }

    public function validateEndPoint(ValidatorDataCollector &$validatorDataCollector) : void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        
        if (array_key_exists($this->validatorDataCollector->resource, $this->availableResourceEndpoints)) {
            $this->setResourceVariables();
            $this->setEndpointData();
        } elseif ($this->validatorDataCollector->resource != 'index') {
            $this->returnEndpointError();
        }
    }

    protected function setResourceVariables() : void
    {
        $this->validatorDataCollector->resourceObject = new $this->availableResourceEndpoints[$this->validatorDataCollector->resource]();
        $this->resourceInfoProvider->setResource($this->validatorDataCollector->resourceObject);
        $this->validatorDataCollector->resourceInfo = $this->resourceInfoProvider->getResourceInfo();
    }

    protected function setEndpointData() : void
    {
        $this->setMainPortionOfEndpointData();
        $this->checkForResourceId();
        $this->validatorDataCollector->setAcceptedParameters([
            "endpoint" => [
                'message' => "\"{$this->validatorDataCollector->resource}\" is a valid resource/endpoint for this API. You can also review available resources/endpoints at " . $this->getIndexUrl()
            ]
        ]);
    }

    protected function setMainPortionOfEndpointData() : void
    {
        $this->validatorDataCollector->endpointData = [
            'resource' => $this->validatorDataCollector->resource,
            'resourceId' => $this->validatorDataCollector->resourceId,
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->validatorDataCollector->url,
            'requestMethod' => $this->validatorDataCollector->requestMethod,
        ];
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

    protected function getIndexUrl() : string
    {
        return substr($this->validatorDataCollector->url, 0, strpos($this->validatorDataCollector->url, 'api/v1/') + 7);
    }
}