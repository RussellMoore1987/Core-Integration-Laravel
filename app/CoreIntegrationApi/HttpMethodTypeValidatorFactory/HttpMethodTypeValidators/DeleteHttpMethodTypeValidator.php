<?php

namespace App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators;

use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators\HttpMethodTypeValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class DeleteHttpMethodTypeValidator implements HttpMethodTypeValidator
{
    public function validateRequest(ValidatorDataCollector $validatorDataCollector, $requestData) : ValidatorDataCollector
    {
        return $validatorDataCollector;
    }
}