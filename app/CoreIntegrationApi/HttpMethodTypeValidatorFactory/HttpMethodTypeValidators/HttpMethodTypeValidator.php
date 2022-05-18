<?php

namespace App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators;

use App\CoreIntegrationApi\ValidatorDataCollector;

interface HttpMethodTypeValidator
{
    public function validateRequest(ValidatorDataCollector $validatorDataCollector, $requestData) : ValidatorDataCollector;
}