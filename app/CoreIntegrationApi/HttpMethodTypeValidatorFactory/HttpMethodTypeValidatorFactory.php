<?php

namespace App\CoreIntegrationApi\HttpMethodTypeValidatorFactory;

use App\CoreIntegrationApi\HttpMethodTypeFactory;
use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators\HttpMethodTypeValidator;

class HttpMethodTypeValidatorFactory extends HttpMethodTypeFactory
{
    public function getFactoryItem($httpMethod) : HttpMethodTypeValidator
    {
        $classPath = 'App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators';

        $this->factoryReturnArray = [
            'get' => "{$classPath}\GetHttpMethodTypeValidator",
            'post' => "{$classPath}\PostHttpMethodTypeValidator",
            'put' => "{$classPath}\PutHttpMethodTypeValidator",
            'patch' => "{$classPath}\PatchHttpMethodTypeValidator",
            'delete' => "{$classPath}\DeleteHttpMethodTypeValidator",
        ];

        return parent::getFactoryItem($httpMethod);
    }
}