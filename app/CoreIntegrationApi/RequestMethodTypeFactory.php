<?php

namespace App\CoreIntegrationApi;

use Illuminate\Support\Facades\App;

abstract class RequestMethodTypeFactory
{
    protected $factoryItem;
    protected $requestMethod;
    // Just placeholder strings, should be replaced by paths to the actual classes, see app\CoreIntegrationApi\RequestMethodTypeFactory\RequestMethodTypeFactory.php for example
    protected $factoryReturnArray = [
        'get' => 'get',
        'post' => 'post',
        'put' => 'put',
        'patch' => 'patch',
        'delete' => 'delete',
    ];

    public function getFactoryItem(string $requestMethod) : object
    {
        $requestMethod = strtolower($requestMethod);
        
        // TODO: test this
        if (!array_key_exists($requestMethod, $this->factoryReturnArray)) {
            throw new \Exception("RequestMethodTypeFactory: Invalid request method type \"$requestMethod\"");
        }

        return $this->returnValue($this->factoryReturnArray[$requestMethod]);
    }  

    protected function returnValue($requestMethodObjectPath)
    {
        return App::make($requestMethodObjectPath);
    }
}