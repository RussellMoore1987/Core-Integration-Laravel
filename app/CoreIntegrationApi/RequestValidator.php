<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\ResourceDataProvider;
use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidatorFactory;
use Illuminate\Http\Exceptions\HttpResponseException;

// ! start here ********************************************************* readability, uml

abstract class RequestValidator 
{

    protected $requestDataPrepper;
    protected $validatorDataCollector;
    protected $resourceDataProvider;
    protected $httpMethodTypeValidatorFactory; // TODO: name might need to change when we add in the context api accessMethodTypeValidatorFactor structure

    protected $resourceObject;
    protected $resourceInfo; // TODO: combine resourceInfo and extraResourceData ??? // see if we are exposing to much information
    protected $resource;
    protected $resourceId;
    protected $parameters;
    
    protected $availableResourceEndpoints;
    protected $endpointError = false;
    protected $validatedMetaData;

    function __construct(RequestDataPrepper $requestDataPrepper, ValidatorDataCollector $validatorDataCollector, ResourceDataProvider $resourceDataProvider, HttpMethodTypeValidatorFactory $httpMethodTypeValidatorFactory) 
    {
        $this->requestDataPrepper = $requestDataPrepper;
        $this->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        $this->validatorDataCollector = $validatorDataCollector;
        $this->resourceDataProvider = $resourceDataProvider;
        $this->httpMethodTypeValidatorFactory = $httpMethodTypeValidatorFactory;
    }   

    public function validate()
    {
        $this->requestDataPrepper->prep();

        $this->validateRequest($this->requestDataPrepper->getPreppedData());

        return $this->validatedMetaData;
    }

    protected function validateRequest($prepRequestData)
    {
        $this->setUpPreppedRequest($prepRequestData);
        
        $this->validateEndPoint();
        $this->setResourceInfo();

        $this->validateHttpRequest();
        
        $this->setResourceInfo();
        $this->setValidatedMetaData();
    }

    protected function setUpPreppedRequest($prepRequestData)
    {
        $this->resource = $prepRequestData['resource'] ?? '';
        $this->resourceId = $prepRequestData['resourceId']  ?? [];
        $this->parameters = $prepRequestData['parameters'] ?? [];
        $this->httpMethod = $prepRequestData['httpMethod'] ?? 'GET';
        $this->url = $prepRequestData['url'] ?? '';
    }

    protected function validateEndPoint()
    {
        if (array_key_exists($this->resource, $this->availableResourceEndpoints) ) { // $this->acceptedEndpoints 
            $this->setRequestClass(); // setRequestClass, setModel, setEndpointModel, setEndpointClass
            $this->setEndpoint();
        } elseif ($this->resource != 'index') {
            $this->setEndpointError();
        } 
    }

    protected function setRequestClass()
    {
        $this->resourceObject = new $this->availableResourceEndpoints[$this->resource]();
        $this->resourceDataProvider->setClass($this->resourceObject);
        $this->resourceInfo = $this->resourceDataProvider->getResourceInfo();
    }

    protected function setEndpoint()
    {
        $this->checkForIdParameterIfThereSetItAppropriately();
        $this->validatorDataCollector->setAcceptedParameter([
            "endpoint" => [
                'message' => "\"{$this->resource}\" is a valid resource/endpoint for this API. You can also review available resources/endpoints at " . $this->getIndexUrl()
            ]
        ]);
    }

    protected function checkForIdParameterIfThereSetItAppropriately()
    {
        $endpointData = [
            'resource' => $this->resource, 
            'resourceId' => $this->resourceId,  
            'endpointError' => $this->endpointError, 
            'class' => $this->resourceInfo['path'], 
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->url,
            'httpMethod' => $this->httpMethod,
        ]; // possibly create new function for this, allow setting to be easier
        if ($this->resourceId) {
            $primaryKeyName = $this->resourceInfo['primaryKeyName']; // TODO: set this for latter, maybe in extraResourceData
            $this->parameters[$primaryKeyName] = $this->resourceId;
            $endpointData['resourceIdConvertedTo'] = [$primaryKeyName => $this->resourceId];
        }
        $this->validatorDataCollector->setEndpointData($endpointData);
    }

    protected function getIndexUrl()
    {
        return substr($this->url, 0, strpos($this->url, 'api/v1/') + 7);
    }

    protected function setEndpointError()
    {
        // TODO: remove this else were
        // $this->endpointError = true;

        $errors = [];
        if ($this->resourceId) {
            $errors = [
                'resourceId' => [
                    'message' => "\"{$this->resource}\" is not a valid resource/endpoint for this API, therefore the resource id is invalid as well. Please review available resources/endpoints at " . $this->getIndexUrl(), 
                    'value' => $this->resourceId
                ]
            ];
        }

        $response = response()->json([
            'error' => 'Invalid Endpoint',
            'errors' => $errors,
            'message' => "\"{$this->resource}\" is not a valid resource/endpoint for this API. Please review available resources/endpoints at " . $this->getIndexUrl(),
            'status_code' => 400,
        ], 400);
        throw new HttpResponseException($response);
    }

    public function validateHttpRequest()
    {
        $requestData = [
            'parameters' => $this->parameters,
            'resourceInfo' => $this->resourceInfo,
            'resourceObject' => $this->resourceObject,
        ];

        $httpMethodTypeValidator = $this->httpMethodTypeValidatorFactory->getFactoryItem($this->httpMethod);
        $this->validatorDataCollector = $httpMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);
    }

    protected function setResourceInfo()
    {
        $this->validatorDataCollector->setResourceInfo($this->resourceInfo);
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