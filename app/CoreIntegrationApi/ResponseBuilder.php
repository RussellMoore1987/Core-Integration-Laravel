<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\HttpMethodResponseBuilderFactory\HttpMethodResponseBuilderFactory;

abstract class ResponseBuilder
{

    function __construct(HttpMethodResponseBuilderFactory $httpMethodResponseBuilderFactory) 
    {
        $this->httpMethodResponseBuilderFactory = $httpMethodResponseBuilderFactory;
    }

    abstract public function setValidatedMetaData($validatedMetaData);
    abstract public function setResponseData($queryResult);
    abstract public function make();
}