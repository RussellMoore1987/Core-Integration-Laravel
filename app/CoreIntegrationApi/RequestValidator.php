<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\ClassDataProvider;

abstract class RequestValidator 
{

    protected $requestDataPrepper;
    protected $validatorDataCollector;
    protected $classDataProvider;
    protected $acceptedClasses;
    protected $parameterValidatorFactory;
    protected $class;
    protected $classInfo;
    protected $endpoint;
    protected $endpointId;
    protected $endpointError = false;
    protected $extraData = [];
    protected $parameters;
    protected $defaultAcceptableParameters = ['per_page', 'perpage', 'page', 'column_data', 'columndata', 'formdata', 'form_data'];
    protected $getMethodParameterValidatorDefaults = [
        'columns' => 'select', 
        'select' => 'select', 
        'orderby' => 'orderby', 
        'order_by' => 'orderby', 
        'methodcalls' => 'methodcalls',
        'method_calls' => 'methodcalls',
        // TODO: add to documentation relationships
        'relationships' => 'includes',
        'includes' => 'includes',
    ];
    protected $validatedMetaData;
    
    function __construct(RequestDataPrepper $requestDataPrepper, ParameterValidatorFactory $parameterValidatorFactory, ValidatorDataCollector $validatorDataCollector, ClassDataProvider $classDataProvider) 
    {
        $this->requestDataPrepper = $requestDataPrepper;
        $this->acceptedClasses = config('coreintegration.acceptedclasses') ?? [];
        $this->parameterValidatorFactory = $parameterValidatorFactory;
        $this->validatorDataCollector = $validatorDataCollector;
        $this->classDataProvider = $classDataProvider;
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

        $this->validateParameters();
        
        $this->setExtraData();
        $this->setValidatedMetaData();
    }

    protected function setUpPreppedRequest($prepRequestData)
    {
        $this->endpoint = $prepRequestData['endpoint'] ?? '';
        $this->endpointId = $prepRequestData['endpointId']  ?? [];
        $this->parameters = $prepRequestData['parameters'] ?? [];
        $this->httpMethod = $prepRequestData['httpMethod'] ?? 'GET';
        $this->url = $prepRequestData['url'] ?? '';
    }

    protected function validateEndPoint()
    {
        if (array_key_exists($this->endpoint, $this->acceptedClasses) ) {
            $this->setRequestClass();
            $this->setEndpoint();
        } elseif ($this->endpoint != 'index') {
            $this->setEndpointError();
        } 
    }

    protected function setRequestClass()
    {
        $this->classDataProvider->setClass($this->acceptedClasses[$this->endpoint]);
        $this->classInfo = $this->classDataProvider->getClassInfo();
    }

    protected function setEndpoint()
    {
        $this->checkForIdParameterIfThereSetItAppropriately();
        $this->validatorDataCollector->setAcceptedParameter([
            "endpoint" => [
                'message' => "\"$this->endpoint\" is a valid endpoint for this API. You can also review available endpoints at " . $this->getIndexUrl()
            ]
        ]);
    }

    protected function checkForIdParameterIfThereSetItAppropriately()
    {
        $endpointData = [
            'endpoint' => $this->endpoint, 
            'endpointId' => $this->endpointId,  
            'endpointError' => $this->endpointError, 
            'class' => $this->classInfo['path'], 
            'indexUrl' => $this->getIndexUrl(),
            'url' => $this->url,
            'httpMethod' => $this->httpMethod,
        ];
        if ($this->endpointId) {
            $primaryKeyName = $this->classInfo['primaryKeyName'];
            $this->parameters[$primaryKeyName] = $this->endpointId;
            $endpointData['endpointIdConvertedTo'] = [$primaryKeyName => $this->endpointId];
        }
        $this->validatorDataCollector->setEndpointData($endpointData);
    }

    protected function getIndexUrl()
    {
        return substr($this->url, 0, strpos($this->url, 'api/v1/') + 7);
    }

    protected function setEndpointError()
    {
        $this->endpointError = true;
        $this->validatorDataCollector->setRejectedParameter([
            'endpoint' => [
                'message' => "\"$this->endpoint\" is not a valid endpoint for this API. Please review available endpoints at " . $this->getIndexUrl()
            ]
        ]);
        if ($this->endpointId) {
            $this->validatorDataCollector->setRejectedParameter([
                'endpointId' => [
                    'message' => "\"$this->endpoint\" is not a valid endpoint for this API, therefore the endpoint ID is invalid as well. Please review available endpoints at " . $this->getIndexUrl(), 
                    'value' => $this->endpointId
                ]
            ]);
        }
        $this->validatorDataCollector->setEndpointData(
            [
                'endpoint' => $this->endpoint, 
                'endpointId' => $this->endpointId,
                'endpointError' => $this->endpointError, 
                'class' => null, 
                'indexUrl' => $this->getIndexUrl(),
                'url' => $this->url,
                'httpMethod' => $this->httpMethod,
            ]
        );
    }

    protected function setClassInfo()
    {
        if (!$this->endpointError) {
            $this->extraData['availableMethodCalls'] = $this->classInfo['classParameterOptions']['availableMethodCalls'];
            $this->extraData['availableIncludes'] = $this->classInfo['classParameterOptions']['availableIncludes'];
            $this->extraData['acceptableParameters'] = $this->classInfo['classParameterOptions']['acceptableParameters'];
        }
    }
    
    // TODO: get validation but what about the others put patch post
    protected function validateParameters()
    {
        foreach ($this->parameters as $key => $value) {
            $key = strtolower($key);
            $data = [$key => $value];
            if (array_key_exists($key, $this->extraData['acceptableParameters'])) {
                $dataType = $this->extraData['acceptableParameters'][$key]['type'];
                $this->getMethodParameterValidator($dataType, $data);
            } elseif (array_key_exists($key, $this->getMethodParameterValidatorDefaults)) {
                $dataType = $this->getMethodParameterValidatorDefaults[$key];
                $this->getMethodParameterValidator($dataType, $data);
            } elseif (in_array($key ,$this->defaultAcceptableParameters)) {
                $this->handleDefaultParameters($key, $value);
            } else {
                $this->validatorDataCollector->setRejectedParameter([
                    $key => [
                        'value' => $value,
                        'parameterError' => 'This is an invalid parameter for this endpoint.'
                    ]
                ]);
            }
        }
    }

    protected function getMethodParameterValidator($dataType, $data)
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem($dataType);
        $this->validatorDataCollector = $parameterValidator->validate($this->validatorDataCollector, $data);
    }

    protected function handleDefaultParameters($key, $value)
    {
        if (in_array($key, ['perpage', 'per_page'])) {
            $this->setPerPageParameter($value);
        } elseif ($key == 'page') {
            $this->setPageParameter($value);
        } elseif (in_array($key, ['columndata', 'column_data'])) {
            $this->validatorDataCollector->setAcceptedParameter([
                'columnData' => [
                    'value' => $value,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter data for this endpoint'
                ]
            ]);
        } elseif (in_array($key, ['formdata', 'form_data'])) {
            $this->validatorDataCollector->setAcceptedParameter([
                'formData' => [
                    'value' => $value,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter form data for this endpoint'
                ]
            ]);
        }
    }

    protected function setPerPageParameter($value)
    {
        if ($this->isInt($value)) {
            $this->validatorDataCollector->setAcceptedParameter([
                'perPage' => (int) $value
            ]);
        } else {
            $this->validatorDataCollector->setRejectedParameter([
                'perPage' => [
                    'value' => $value,
                    'parameterError' => 'This parameter\'s value must be an int.'
                ]
            ]);
        }
    }
    
    protected function setPageParameter($value)
    {
        if ($this->isInt($value)) {
            $this->validatorDataCollector->setAcceptedParameter([
                'page' => (int) $value
            ]);
        } else {
            $this->validatorDataCollector->setRejectedParameter([
                'page' => [
                    'value' => $value,
                    'parameterError' => 'This parameter\'s value must be an int.'
                ]
            ]);
        }
    }

    protected function isInt($value)
    {
        return is_numeric($value) && !str_contains($value, '.');
    }

    protected function setExtraData()
    {
        $this->validatorDataCollector->setExtraData($this->extraData);
    }

    protected function setValidatedMetaData()
    {
        $this->validatedMetaData = $this->validatorDataCollector->getAllData();
    }

    public function getValidatedQueryData() 
    {
        return $this->validatedMetaData;
    }
}