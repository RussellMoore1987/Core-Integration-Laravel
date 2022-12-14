<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators;

use App\CoreIntegrationApi\ValidatorDataCollector;

interface RequestMethodTypeValidator
{
    public function validateRequest(ValidatorDataCollector &$validatorDataCollector) : void;
}