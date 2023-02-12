<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\Exceptions\RequestMethodTypeValidatorFactoryException;
use Illuminate\Support\Facades\App;

abstract class RequestMethodTypeFactory
{
    protected $requestMethod;
    // Just placeholder strings, should be replaced by paths to the actual classes, see app\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory.php for an example
    protected $factoryReturnArray = [
        'get' => 'get',
        'post' => 'post',
        'put' => 'put',
        'patch' => 'patch',
        'delete' => 'delete',
    ];

    public function getFactoryItem(string $requestMethod): object
    {
        $requestMethod = strtolower($requestMethod);
        
        if (!array_key_exists($requestMethod, $this->factoryReturnArray)) {
            throw new RequestMethodTypeValidatorFactoryException("RequestMethodTypeFactory: Invalid request method type \"$requestMethod\"");
        }

        return App::make($this->factoryReturnArray[$requestMethod]);
    }
}