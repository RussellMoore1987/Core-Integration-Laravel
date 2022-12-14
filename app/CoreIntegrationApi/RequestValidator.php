<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;

// ! start here ********************************************************* readability, uml
// TODO: take small steps in refactoring new structure *********************** test speed****

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
        $this->EndpointValidator = $endpointValidator;
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

        $this->validateRequestMethod();
        
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

    protected function validateRequestMethod()
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