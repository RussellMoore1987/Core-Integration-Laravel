<?php

namespace App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators;

use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators\HttpMethodTypeValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class PostHttpMethodTypeValidator implements HttpMethodTypeValidator
{
    public function validateRequest(ValidatorDataCollector $validatorDataCollector, $requestData) : ValidatorDataCollector
    {
        // dd($requestData);

        $this->validatorDataCollector = $validatorDataCollector;

        $this->parameters = $requestData['parameters'];
        $this->extraData = $requestData['extraData'];
        $this->classObject = $requestData['classObject'];

        $this->validateParameters();

        return $validatorDataCollector;
    }

    protected function validateParameters() : void
    {
        $this->setUpValidationRules();
        // $this->validate();
    }

    protected function setUpValidationRules() : void
    {
        // ! start here ********************************************** getting validation rules from class or default validation rules
        // set required earlier
        $validationRules = $this->classObject;
        foreach ($this->parameters as $key => $value) {
            # code...
        }
    }
}