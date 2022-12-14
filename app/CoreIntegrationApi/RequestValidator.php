<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;

// ! start here ********************************************************* readability, uml

abstract class RequestValidator
{
    protected $requestDataPrepper;
    protected $validatorDataCollector;
    protected $requestMethodTypeValidatorFactory;
    protected $validatedMetaData;

    public function __construct(RequestDataPrepper $requestDataPrepper, ValidatorDataCollector $validatorDataCollector, EndpointValidator $endpointValidator, RequestMethodTypeValidatorFactory $requestMethodTypeValidatorFactory)
    {
        $this->requestDataPrepper = $requestDataPrepper;
        $this->validatorDataCollector = $validatorDataCollector; // * passed by reference to all methods
        $this->validatorDataCollector->availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        $this->requestMethodTypeValidatorFactory = $requestMethodTypeValidatorFactory;
        $this->endpointValidator = $endpointValidator;
    }

    public function validate()
    {
        $this->requestDataPrepper->prep();

        $this->validateRequest($this->requestDataPrepper->getPreppedData());

        return $this->validatedMetaData;
    }

    protected function validateRequest($preppedRequestData)
    {
        $this->setUpPreppedDataForValidation($preppedRequestData);
        
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $this->validateByRequestMethod();
        
        $this->setValidatedMetaData();
    }

    protected function setUpPreppedDataForValidation($preppedRequestData)
    {
        $this->validatorDataCollector->resource = $preppedRequestData['resource'] ?? '';
        $this->validatorDataCollector->resourceId = $preppedRequestData['resourceId']  ?? [];
        $this->validatorDataCollector->parameters = $preppedRequestData['parameters'] ?? [];
        $this->validatorDataCollector->requestMethod = $preppedRequestData['requestMethod'] ?? 'GET';
        $this->validatorDataCollector->url = $preppedRequestData['url'] ?? '';
    }

    protected function validateByRequestMethod()
    {
        $requestMethodTypeValidator = $this->requestMethodTypeValidatorFactory->getFactoryItem($this->validatorDataCollector->requestMethod);
        $requestMethodTypeValidator->validateRequest($this->validatorDataCollector);
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