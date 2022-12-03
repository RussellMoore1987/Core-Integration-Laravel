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
    protected $httpMethodTypeValidatorFactory; // TODO: name might need to change when we add in the context api RequestMethodTypeValidatorFactory structure

    protected $resourceObject;
    protected $resourceInfo;
    protected $resource;
    protected $resourceId;
    protected $parameters;
    
    protected $availableResourceEndpoints;
    protected $endpointData;
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
        $this->httpMethod = $prepRequestData['httpMethod'] ?? 'GET';
        $this->url = $prepRequestData['url'] ?? '';
    }

    protected function validateEndPoint()
    {
        if (array_key_exists($this->resource, $this->availableResourceEndpoints) ) {
            $this->setResourceVariables(); // ! start here *****************************************
            $this->setEndpointDataInValidatorDataCollector();
        } elseif ($this->resource != 'index') {
            $this->returnEndpointError();
        } 
    }

    protected function setResourceVariables()
    {
        $this->resourceObject = new $this->availableResourceEndpoints[$this->resource]();
        $this->resourceDataProvider->setClass($this->resourceObject);
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

    protected function setEndpointData()
    {
        $this->endpointData = [
            'resource' => $this->resource, 
            'resourceId' => $this->resourceId,  
            'class' => $this->resourceInfo['path'], 
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->url,
            'httpMethod' => $this->httpMethod, // TODO: name might need to change when we add in the context api accessMethodTypeValidatorFactor structure
        ]; 
        $this->checkForResourceId();
        $this->validatorDataCollector->setEndpointData($this->endpointData);
    }

    protected function getIndexUrl()
    {
        return substr($this->url, 0, strpos($this->url, 'api/v1/') + 7);
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
        $errors = []; // TODO: fix this werd error stuff, do I need this part ???, no I don't think so
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