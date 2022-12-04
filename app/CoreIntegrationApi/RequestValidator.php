<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\ResourceDataProvider;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;
use Illuminate\Http\Exceptions\HttpResponseException;

// ! start here ********************************************************* readability, uml

abstract class RequestValidator 
{

    protected $requestDataPrepper;
    protected $validatorDataCollector;
    protected $resourceDataProvider;
    protected $requestMethodTypeValidatorFactory; // TODO: name might need to change when we add in the context api RequestMethodTypeValidatorFactory structure GET|Retrieve, POST|Create, PUT|Replace, PATCH|Update DELETE

    protected $resourceObject;
    protected $resourceInfo;
    protected $resource;
    protected $resourceId;
    protected $parameters;
    
    protected $availableResourceEndpoints;
    protected $endpointData;
    protected $validatedMetaData;

    function __construct(RequestDataPrepper $requestDataPrepper, ValidatorDataCollector $validatorDataCollector, ResourceDataProvider $resourceDataProvider, RequestMethodTypeValidatorFactory $requestMethodTypeValidatorFactory) 
    {
        $this->requestDataPrepper = $requestDataPrepper;
        $this->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        $this->validatorDataCollector = $validatorDataCollector;
        $this->resourceDataProvider = $resourceDataProvider;
        $this->requestMethodTypeValidatorFactory = $requestMethodTypeValidatorFactory;
    }   

    public function validate()
    {
        $this->requestDataPrepper->prep();

        $this->validateRequest($this->requestDataPrepper->getPreppedData());

        return $this->validatedMetaData;
    }

    protected function validateRequest($prepRequestData)
    {
        $this->setUpPreppedDataForValidation($prepRequestData);
        
        $this->validateEndPoint();
        $this->setResourceInfo();

        $this->validateHttpRequest();
        
        $this->setValidatedMetaData();
    }

    protected function setUpPreppedDataForValidation($prepRequestData)
    {
        $this->resource = $prepRequestData['resource'] ?? '';
        $this->resourceId = $prepRequestData['resourceId']  ?? [];
        $this->parameters = $prepRequestData['parameters'] ?? [];
        $this->requestMethod = $prepRequestData['requestMethod'] ?? 'GET';
        $this->url = $prepRequestData['url'] ?? '';
    }

    protected function validateEndPoint()
    {
        if (array_key_exists($this->resource, $this->availableResourceEndpoints) ) {
            $this->setResourceVariables();
            $this->setEndpointDataInValidatorDataCollector();
        } elseif ($this->resource != 'index') {
            $this->returnEndpointError();
        } 
    }

    protected function setResourceVariables()
    {
        $this->resourceObject = new $this->availableResourceEndpoints[$this->resource]();
        $this->resourceDataProvider->setResource($this->resourceObject);
        $this->resourceInfo = $this->resourceDataProvider->getResourceInfo();
    }

    protected function setEndpointDataInValidatorDataCollector()
    {
        $this->setEndpointData();
        $this->validatorDataCollector->setAcceptedParameter([
            "endpoint" => [
                'message' => "\"{$this->resource}\" is a valid resource/endpoint for this API. You can also review available resources/endpoints at " . $this->getIndexUrl()
            ]
        ]);
    }

    protected function getIndexUrl()
    {
        return substr($this->url, 0, strpos($this->url, 'api/v1/') + 7);
    }

    protected function setEndpointData()
    {
        $this->endpointData = [
            'resource' => $this->resource, 
            'resourceId' => $this->resourceId,  
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->url,
            'requestMethod' => $this->requestMethod, // TODO: name might need to change when we add in the context api accessMethodTypeValidatorFactor structure
        ]; 
        $this->checkForResourceId();
        $this->validatorDataCollector->setEndpointData($this->endpointData);
    }

    protected function checkForResourceId()
    {
        if ($this->resourceId) {
            $primaryKeyName = $this->resourceInfo['primaryKeyName'];
            $this->parameters[$primaryKeyName] = $this->resourceId;
            $this->endpointData['resourceIdConvertedTo'] = [$primaryKeyName => $this->resourceId];
        }
    }

    protected function returnEndpointError()
    {
        $response = response()->json([
            'error' => 'Invalid Endpoint',
            'message' => "\"{$this->resource}\" is not a valid resource/endpoint for this API. Please review available resources/endpoints at " . $this->getIndexUrl(),
            'status_code' => 400,
        ], 400);
        throw new HttpResponseException($response);
    }

    protected function setResourceInfo()
    {
        $this->validatorDataCollector->setResourceInfo($this->resourceInfo);
    }

    public function validateHttpRequest()
    {
        $requestData = [
            'parameters' => $this->parameters,
            'resourceInfo' => $this->resourceInfo,
            'resourceObject' => $this->resourceObject,
        ];

        $requestMethodTypeValidator = $this->requestMethodTypeValidatorFactory->getFactoryItem($this->requestMethod);
        $this->validatorDataCollector = $requestMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);
    }

    protected function setValidatedMetaData()
    {
        $this->validatedMetaData = $this->validatorDataCollector->getAllData();
    }

    public function getValidatedMetaData() 
    {
        return $this->validatedMetaData;
    }
}