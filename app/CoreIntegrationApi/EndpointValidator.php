<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ResourceModelInfoProvider;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\CoreIntegrationApi\ValidatorDataCollector;

// ! Start here ****************************************************************** readability, test coverage, test organization, tests grouping, go one by one

class EndpointValidator
{
    protected $resourceModelInfoProvider;
    protected $validatorDataCollector;
    protected $availableResourceEndpoints;

    public function __construct(ResourceModelInfoProvider $resourceModelInfoProvider)
    {
        $this->resourceModelInfoProvider = $resourceModelInfoProvider;
    }

    public function validateEndPoint(ValidatorDataCollector &$validatorDataCollector) : void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        
        if (array_key_exists($this->validatorDataCollector->resource, $this->availableResourceEndpoints)) {
            // TODO: Test this
            $this->setResourceVariables();
            // TODO: Test this
            $this->setEndpointData();
        } elseif ($this->validatorDataCollector->resource != 'index') {
            // TODO: Test this
            $this->returnEndpointError();
        }
    }

    protected function setResourceVariables() : void
    {
        // TODO: Test this, is set, is the class
        $this->validatorDataCollector->resourceObject = new $this->availableResourceEndpoints[$this->validatorDataCollector->resource]();
        $this->resourceModelInfoProvider->setResource($this->validatorDataCollector->resourceObject);
        // TODO: Test this, top layer, key
        $this->validatorDataCollector->resourceInfo = $this->resourceModelInfoProvider->getResourceInfo();
    }

    protected function setEndpointData() : void
    {
        $this->setMainPortionOfEndpointData();
        $this->checkForResourceId();
        // TODO: Test this, is set, details set
        $this->validatorDataCollector->setAcceptedParameters([
            "endpoint" => [
                'message' => "\"{$this->validatorDataCollector->resource}\" is a valid resource/endpoint for this API. You can also review available resources/endpoints at " . $this->getIndexUrl()
            ]
        ]);
    }

    protected function setMainPortionOfEndpointData() : void
    {
        // TODO: Test this, is set, details set
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
        // TODO: Test this, is set, details set
        if ($this->validatorDataCollector->resourceId) {
            $primaryKeyName = $this->validatorDataCollector->resourceInfo['primaryKeyName'];
            $this->validatorDataCollector->parameters[$primaryKeyName] = $this->validatorDataCollector->resourceId;
            $this->validatorDataCollector->endpointData['resourceIdConvertedTo'] = [$primaryKeyName => $this->validatorDataCollector->resourceId];
        }
    }

    protected function returnEndpointError()
    {
        // TODO: Test this, is thrown
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