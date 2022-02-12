<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;

abstract class RequestValidator 
{

    private $requestDataPrepper;
    private $validatorDataCollector;
    private $acceptedClasses;
    private $parameterValidatorFactory;
    private $class;
    private $endpoint;
    private $endpointId;
    private $parameters;
    private $defaultAcceptableParameters = [
        'orderby' => 'orderby', 
        'perpage' => 'perpage', 
        'select' => 'select', 
        'page' => 'page',
    ];
    private $acceptableParameters;
    private $validatedMetaData;
    
    function __construct(RequestDataPrepper $requestDataPrepper, ParameterValidatorFactory $parameterValidatorFactory, ValidatorDataCollector $validatorDataCollector) 
    {
        $this->requestDataPrepper = $requestDataPrepper;
        $this->acceptedClasses = config('coreintegration.acceptedclasses') ?? [];
        $this->parameterValidatorFactory = $parameterValidatorFactory;
        $this->validatorDataCollector = $validatorDataCollector;
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
        $this->getAcceptableParameters();
        $this->validateParameters();

        $this->setValidatedMetaData();
    }

    protected function setUpPreppedRequest($prepRequestData)
    {
        $this->class = $prepRequestData['class'] ?? '';
        $this->endpoint = $prepRequestData['endpoint'] ?? '';
        $this->endpointId = $prepRequestData['endpointId']  ?? []; // may be set in the prepper
        $this->parameters = $prepRequestData['parameters'] ?? [];
    }

    // TODO: Returns database data type with validated information
    // TODO: when finding acceptable parameters, Besides the default, apply parameter type to array of parameter information

    protected function getAcceptableParameters()
    {
        // set $this->acceptableParameters
    }

    protected function validateEndPoint()
    {
        // see if end point is in config('coreintegration.acceptedclasses')
    }

    // get validation but what about the others put patch post
    protected function validateParameters()
    {
        $allAcceptableParameters = array_merge($this->acceptableParameters, $this->defaultAcceptableParameters);

        foreach ($this->parameters as $key => $value) {
            if (array_key_exists($key, $allAcceptableParameters)) {
                $parameterValidator = $this->parameterValidatorFactory->getParameterValidator($allAcceptableParameters[$key]['type'] ?? $allAcceptableParameters[$key]);
                $this->validatorDataCollector = $parameterValidator->validate($this->validatorDataCollector, [$key => $value]);
            } else {
                $this->validatorDataCollector->setRejectedParameter([
                    $key => [
                        $key => $value,
                        'parameterError' => 'This is an invalid parameter for this endpoint.'
                    ]
                ]);
            }
        }
        // code...
        // use $this->acceptableParameters
        // use $this->defaultAcceptableParameters
        // Run them through a data preper or Parameter validator
        // All parameter validation needs to be done here

        // Use parameter validator factory
    }

    protected function setValidatedMetaData()
    {
        $validatedRequestMetaData['rejectedParameters'] = $this->validatorDataCollector->getRejectedParameters();
        $validatedRequestMetaData['acceptedParameters'] = $this->validatorDataCollector->getAcceptedParameters();
        // $validatedRequestMetaData['queryArguments'] = $this->validatorDataCollector->getQueryArguments(); // don't know if we need to send this one
        $this->validatedMetaData = $validatedRequestMetaData;

        $this->validatorDataCollector->reset();
    }

    // I don't know if we need this
    // public function getValidatedQueryData() 
    // {
    //     return $this->validatedMetaData;
    // }
}