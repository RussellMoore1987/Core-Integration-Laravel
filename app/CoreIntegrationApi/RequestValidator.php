<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\ClassDataProvider;
use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidatorFactory;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class RequestValidator 
{

    protected $requestDataPrepper;
    protected $validatorDataCollector;
    protected $classDataProvider;
    protected $httpMethodTypeValidatorFactory;

    protected $classObject;
    protected $classInfo;
    protected $acceptedClasses; // TODO: chang name availableResourceEndpoints***, exposedResource, acceptedEndpoints, resourceEndpoints, resourceRoutes
    protected $resource;
    protected $resourceId;
    protected $endpointError = false;
    protected $extraData = [];
    protected $parameters;
    protected $validatedMetaData;

    function __construct(RequestDataPrepper $requestDataPrepper, ValidatorDataCollector $validatorDataCollector, ClassDataProvider $classDataProvider, HttpMethodTypeValidatorFactory $httpMethodTypeValidatorFactory) 
    {
        $this->requestDataPrepper = $requestDataPrepper;
        $this->acceptedClasses = config('coreintegration.acceptedclasses') ?? [];
        $this->validatorDataCollector = $validatorDataCollector;
        $this->classDataProvider = $classDataProvider;
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
        $this->setClassInfo();

        $this->validateHttpRequest();
        
        $this->setExtraData();
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
        if (array_key_exists($this->resource, $this->acceptedClasses) ) { // $this->acceptedEndpoints 
            $this->setRequestClass(); // setRequestClass, setModel, setEndpointModel, setEndpointClass
            $this->setEndpoint();
        } elseif ($this->resource != 'index') {
            $this->setEndpointError();
        } 
    }

    protected function setRequestClass()
    {
        $this->classObject = new $this->acceptedClasses[$this->resource]();
        $this->classDataProvider->setClass($this->classObject);
        $this->classInfo = $this->classDataProvider->getClassInfo();
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
            'class' => $this->classInfo['path'], 
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->url,
            'httpMethod' => $this->httpMethod,
        ]; // possibly create new function for this, allow setting to be easier
        if ($this->resourceId) {
            $primaryKeyName = $this->classInfo['primaryKeyName'];
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

    protected function setClassInfo()
    {
        if (!$this->endpointError) {
            $this->extraData['availableMethodCalls'] = $this->classInfo['classParameterOptions']['availableMethodCalls'];
            $this->extraData['availableIncludes'] = $this->classInfo['classParameterOptions']['availableIncludes'];
            $this->extraData['acceptableParameters'] = $this->classInfo['classParameterOptions']['acceptableParameters'];
        }
    }

    public function validateHttpRequest()
    {
        $requestData = [
            'parameters' => $this->parameters,
            'extraData' => $this->extraData,
            'classObject' => $this->classObject,
        ];

        $httpMethodTypeValidator = $this->httpMethodTypeValidatorFactory->getFactoryItem($this->httpMethod);
        $this->validatorDataCollector = $httpMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);
    }

    protected function setExtraData()
    {
        $this->validatorDataCollector->setExtraData($this->extraData);
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