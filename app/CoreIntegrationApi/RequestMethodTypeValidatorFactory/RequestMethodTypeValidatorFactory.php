<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory;

use App\CoreIntegrationApi\RequestMethodTypeFactory;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\RequestMethodTypeValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\Get\GetRequestMethodTypeValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\PostRequestMethodTypeValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\PutRequestMethodTypeValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\PatchRequestMethodTypeValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\DeleteRequestMethodTypeValidator;

class RequestMethodTypeValidatorFactory extends RequestMethodTypeFactory
{
    protected $factoryReturnArray = [
        'get' => GetRequestMethodTypeValidator::class,
        'post' => PostRequestMethodTypeValidator::class,
        'put' => PutRequestMethodTypeValidator::class,
        'patch' => PatchRequestMethodTypeValidator::class,
        'delete' => DeleteRequestMethodTypeValidator::class,
    ];

    public function getFactoryItem($requestMethod): RequestMethodTypeValidator
    {
        return parent::getFactoryItem($requestMethod);
    }
}
