<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class FloatParameterValidator implements ParameterValidator
{
    public function validate(ValidatorDataCollector &$validatorDataCollector, $parameterData) : void
    {
        $this->validatorDataCollector = $validatorDataCollector; 
    }
}