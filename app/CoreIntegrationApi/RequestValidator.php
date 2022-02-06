<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;

abstract class RequestValidator 
{

    protected $requestDataPrepper;
    protected $acceptableParameters;
    protected $class;
    protected $endpoint;
    protected $validatedMetaData;
    

    // TODO: maybe make Validator data collector 
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

    protected function validateRequest($request)
    {
        $this->setUpPreppedRequest($request);
        
        $this->validateEndPoint();
        $this->getAcceptableParameters();
        $this->validateParameters();

        $this->setValidatedMetaData();
    }

    protected function setUpPreppedRequest($request)
    {
        $this->class = $request['class'];
        $this->endpoint = $request['endpoint'];
        $this->endpointId = $request['endpointId'];
        $this->parameters = $request['parameters'] ?? [];
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

    protected function validateParameters()
    {
        $allAcceptableParameters = array_merge($this->acceptableParameters, $this->defaultAcceptableParameters);

        foreach ($this->parameters as $key => $value) {
            if (array_key_exists($key, $allAcceptableParameters)) {
                $ParameterValidator = $this->ParameterValidatorFactory->getParameterValidator($allAcceptableParameters[$key]['type'] ?? $allAcceptableParameters[$key]);
                $this->validatorDataCollector = $ParameterValidator->validate($this->validatorDataCollector, [$key => $value]);
            } else {
                $this->validatorDataCollector->setRejectedParameter([$key => $value], 'This is an invalid parameter for this endpoint.');
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
        $validatedRequestMetaData['rejectedParameters'] = $this->getRejectedParameters();
        $validatedRequestMetaData['acceptedParameters'] = $this->getAcceptedParameters();
        $validatedRequestMetaData['errors'] = $this->errors;
        $validatedRequestMetaData['queryArguments'] = $this->getQueryArguments();
        $this->validatedMetaData = $validatedRequestMetaData;
    }

    public function getValidatedQueryData()
    {

    }
}