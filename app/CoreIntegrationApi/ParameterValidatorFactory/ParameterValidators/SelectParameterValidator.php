<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class SelectParameterValidator implements ParameterValidator
{
    protected $validatorDataCollector;

    public function validate(string $parameterName, string $parameterValue, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;

        // just for testing purposes
        $this->validatorDataCollector->setAcceptedParameters([$parameterName => $parameterValue]);
        $this->validatorDataCollector->setQueryArgument([$parameterName => $parameterValue]);
    }
}
