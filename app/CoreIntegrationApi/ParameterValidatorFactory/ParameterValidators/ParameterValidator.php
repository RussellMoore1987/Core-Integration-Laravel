<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ValidatorDataCollector;

interface ParameterValidator
{
    public function validate(string $parameterName, string $parameterValue, ValidatorDataCollector &$validatorDataCollector): void;
}
