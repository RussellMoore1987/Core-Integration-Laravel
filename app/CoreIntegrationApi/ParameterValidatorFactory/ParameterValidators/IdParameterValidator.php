<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class IdParameterValidator implements ParameterValidator
{
    public function validate(ValidatorDataCollector $validatorDataCollector, $parameterData) : ValidatorDataCollector
    {
        $this->validatorDataCollector = $validatorDataCollector; 

        return $this->validatorDataCollector;
    }
}