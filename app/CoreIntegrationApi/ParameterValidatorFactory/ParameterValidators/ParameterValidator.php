<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;
use App\CoreIntegrationApi\ValidatorDataCollector;

interface ParameterValidator
{
    public function validate(ValidatorDataCollector $validatorDataCollector, $parameterData) : ValidatorDataCollector;
}