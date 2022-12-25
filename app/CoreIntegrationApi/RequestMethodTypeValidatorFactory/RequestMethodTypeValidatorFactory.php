<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory;

use App\CoreIntegrationApi\RequestMethodTypeFactory;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\RequestMethodTypeValidator;

class RequestMethodTypeValidatorFactory extends RequestMethodTypeFactory
{
    public function getFactoryItem($requestMethod) : RequestMethodTypeValidator
    {
        $classPath = 'App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators';

        $this->factoryReturnArray = [
            'get' => "{$classPath}\GetRequestMethodTypeValidator",
            'post' => "{$classPath}\PostRequestMethodTypeValidator",
            'put' => "{$classPath}\PutRequestMethodTypeValidator",
            'patch' => "{$classPath}\PatchRequestMethodTypeValidator",
            'delete' => "{$classPath}\DeleteRequestMethodTypeValidator",
        ];

        return parent::getFactoryItem($requestMethod);
    }
}