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
        $this->httpMethod = strtolower($httpMethod);
        
        $this->factoryItem = null;

        $this->checkForGetRequest();
        $this->checkForPostRequest();
        $this->checkForPutRequest();
        $this->checkForPatchRequest();
        $this->checkForDeleteRequest();

        return $this->factoryItem;
    }  

    protected function checkForGetRequest()
    {
        if ($this->httpMethod == 'get') {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['get']);
        }
    }

    protected function checkForPostRequest()
    {
        if ($this->httpMethod == 'post') {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['post']);
        }
    }

    protected function checkForPutRequest()
    {
        if ($this->httpMethod == 'put') {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['put']);
        }
    }

    protected function checkForPatchRequest()
    {
        if ($this->httpMethod == 'patch') {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['patch']);
        }
    }

    protected function checkForDeleteRequest()
    {
        if ($this->httpMethod == 'delete') {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['delete']);
        }
    }

    protected function returnValue($httpMethodObjectPath)
    {
        return App::make($httpMethodObjectPath);
    }
}