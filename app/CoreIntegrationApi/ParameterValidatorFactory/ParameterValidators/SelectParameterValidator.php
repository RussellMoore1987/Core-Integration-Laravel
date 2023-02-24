<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class SelectParameterValidator implements ParameterValidator
{
    protected ValidatorDataCollector $validatorDataCollector;

    public function validate(ValidatorDataCollector &$validatorDataCollector, $parameterData): void
    {
        $this->validatorDataCollector = $validatorDataCollector;

        $this->validatorDataCollector->setAcceptedParameters($parameterData);
        $this->validatorDataCollector->setQueryArgument($parameterData);
    }
}