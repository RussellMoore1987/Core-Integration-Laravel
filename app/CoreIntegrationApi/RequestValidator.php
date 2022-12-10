<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\ResourceDataProvider;
use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;
use Illuminate\Support\Facades\App;

// ! start here ********************************************************* readability, uml
// TODO: take small steps in refactoring new structure *********************** test speed**** ??? one commit back ???

abstract class RequestValidator 
{

    protected $requestDataPrepper;
    protected $validatorDataCollector;
    protected $resourceDataProvider;
    protected $requestMethodTypeValidatorFactory; // TODO: add to factory if statement structure GET|Retrieve, POST|Create, PUT|Replace, PATCH|Update DELETE

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
        $this->validatorDataCollector = $validatorDataCollector; // passed by reference to all methods
        $this->validatorDataCollector->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        $this->resourceDataProvider = $resourceDataProvider;
        $this->requestMethodTypeValidatorFactory = $requestMethodTypeValidatorFactory;
        $this->EndpointValidator = App::make(EndpointValidator::class); // TODO: fix this***
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
        
        $this->EndpointValidator->validateEndPoint($this->validatorDataCollector);

        $this->validateHttpRequest();
        
        $this->setValidatedMetaData();
    }

    protected function setUpPreppedDataForValidation($prepRequestData)
    {
        $this->validatorDataCollector->resource = $prepRequestData['resource'] ?? '';
        $this->validatorDataCollector->resourceId = $prepRequestData['resourceId']  ?? [];
        $this->validatorDataCollector->parameters = $prepRequestData['parameters'] ?? [];
        $this->validatorDataCollector->requestMethod = $prepRequestData['requestMethod'] ?? 'GET';
        $this->validatorDataCollector->url = $prepRequestData['url'] ?? '';
    }

    public function validateHttpRequest()
    {
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