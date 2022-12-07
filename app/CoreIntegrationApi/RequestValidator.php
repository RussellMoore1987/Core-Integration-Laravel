<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\ResourceDataProvider;
use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;

// ! start here ********************************************************* readability, uml
// TODO: take small steps in refactoring new structure *********************** test speed

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
        $this->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? []; // TODO: remove
        $this->validatorDataCollector = $validatorDataCollector; // passed by reference to methods
        $this->validatorDataCollector->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        $this->resourceDataProvider = $resourceDataProvider;
        $this->requestMethodTypeValidatorFactory = $requestMethodTypeValidatorFactory;



        $this->EndpointValidator = App::make(EndpointValidator::class);
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
        
        // ! switch to fix
        // $this->validateEndPoint();
        // $this->setResourceInfo();
        // * new way
        $this->EndpointValidator->validateEndPoint($this->validatorDataCollector);



        $this->validateHttpRequest();
        
        $this->setValidatedMetaData();
    }

    protected function setUpPreppedDataForValidation($prepRequestData)
    {
        // ! switch to fix
        // $this->resource = $prepRequestData['resource'] ?? '';
        // $this->resourceId = $prepRequestData['resourceId']  ?? [];
        // $this->parameters = $prepRequestData['parameters'] ?? [];
        // $this->requestMethod = $prepRequestData['requestMethod'] ?? 'GET';
        // $this->url = $prepRequestData['url'] ?? '';
        // * new way
        $this->validatorDataCollector->resource = $prepRequestData['resource'] ?? '';
        $this->validatorDataCollector->resourceId = $prepRequestData['resourceId']  ?? [];
        $this->validatorDataCollector->parameters = $prepRequestData['parameters'] ?? [];
        $this->validatorDataCollector->requestMethod = $prepRequestData['requestMethod'] ?? 'GET';
        $this->validatorDataCollector->url = $prepRequestData['url'] ?? '';
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
        // ! switch to fix
        // $requestData = [
        //     'parameters' => $this->parameters,
        //     'resourceInfo' => $this->resourceInfo,
        //     'resourceObject' => $this->resourceObject,
        // ];

        // $requestMethodTypeValidator = $this->requestMethodTypeValidatorFactory->getFactoryItem($this->requestMethod);
        // $requestMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);

        // * new way
        $requestData = [
            'parameters' => $this->validatorDataCollector->parameters,
            'resourceInfo' => $this->validatorDataCollector->resourceInfo,
            'resourceObject' => $this->validatorDataCollector->resourceObject,
        ];

        $requestMethodTypeValidator = $this->requestMethodTypeValidatorFactory->getFactoryItem($this->validatorDataCollector->requestMethod);
        $requestMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);
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