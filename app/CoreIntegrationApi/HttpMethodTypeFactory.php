<?php

namespace App\CoreIntegrationApi;

use Illuminate\Support\Facades\App;

abstract class HttpMethodTypeFactory
{
    protected $factoryItem;
    protected $httpMethod;
    // Just placeholder strings, should be replaced by paths to the actual classes, see app\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidatorFactory.php for example
    protected $factoryReturnArray = [
        'get' => 'get',
        'post' => 'post',
        'put' => 'put',
        'patch' => 'patch',
        'delete' => 'delete',
    ];

    public function getFactoryItem($httpMethod) : object
    {
        $httpMethod = strtolower($httpMethod);
        
        // TODO: test this
        if (!array_key_exists($httpMethod, $this->factoryReturnArray)) {
            throw new \Exception('HttpMethodTypeFactory: Invalid http method type');
        }

        return $this->returnValue($this->factoryReturnArray[$httpMethod]);
    }  

    protected function returnValue($httpMethodObjectPath)
    {
        return App::make($httpMethodObjectPath);
    }
}