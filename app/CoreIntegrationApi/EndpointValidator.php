<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ResourceModelInfoProvider;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;

class EndpointValidator
{
    protected $resourceModelInfoProvider;
    protected $validatorDataCollector;
    protected $availableResourceEndpoints;

    public function __construct(ResourceModelInfoProvider $resourceModelInfoProvider)
    {
        $this->resourceModelInfoProvider = $resourceModelInfoProvider;
        $this->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
    }

    public function validateEndPoint(ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        
        if (array_key_exists($this->validatorDataCollector->resource, $this->availableResourceEndpoints)) {
            $this->setResourceVariables();
            $this->setEndpointData();
        } elseif ($this->validatorDataCollector->resource != 'index') {
            $this->returnEndpointError();
        }
    }

    protected function setResourceVariables(): void
    {
        $this->validatorDataCollector->resourceObject = new $this->availableResourceEndpoints[$this->validatorDataCollector->resource]();
        $this->validatorDataCollector->resourceInfo = $this->resourceModelInfoProvider->getResourceInfo($this->validatorDataCollector->resourceObject); // TODO: this will brake if not a model
    }

    protected function setEndpointData(): void
    {
        $this->setMainPortionOfEndpointData();
        $this->setResourceId();
        $this->validatorDataCollector->setAcceptedParameters([
            "endpoint" => [
                'message' => "\"{$this->validatorDataCollector->resource}\" is a valid resource/endpoint for this API. You can also review available resources/endpoints at " . $this->getIndexUrl()
            ]
        ]);
    }

    protected function setMainPortionOfEndpointData(): void
    {
        $this->validatorDataCollector->endpointData = [
            'resource' => $this->validatorDataCollector->resource,
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->validatorDataCollector->url,
            'requestMethod' => $this->validatorDataCollector->requestMethod,
        ];
    }

    protected function setResourceId(): void
    {
        $this->validatorDataCollector->endpointData['resourceId'] = $this->validatorDataCollector->resourceId;

        if ($this->validatorDataCollector->resourceId) {
            $primaryKeyName = $this->validatorDataCollector->resourceInfo['primaryKeyName'];
            $this->validatorDataCollector->parameters[$primaryKeyName] = $this->validatorDataCollector->resourceId;
            $this->validatorDataCollector->endpointData['resourceIdConvertedTo'] = [$primaryKeyName => $this->validatorDataCollector->resourceId];
        }
    }

    protected function returnEndpointError(): void
    {
        $response = response()->json([
            'error' => 'Resource/Endpoint Not Found',
            'message' => "\"{$this->validatorDataCollector->resource}\" is not a valid resource/endpoint for this API. Please review available resources/endpoints at " . $this->getIndexUrl(),
            'statusCode' => 404,
        ], 404);
        throw new HttpResponseException($response);
    }

    protected function getIndexUrl(): string
    {
        return substr($this->validatorDataCollector->url, 0, strpos($this->validatorDataCollector->url, 'api/v1/') + 7);
    }
}
