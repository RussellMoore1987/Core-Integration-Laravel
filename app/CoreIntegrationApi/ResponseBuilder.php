<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilderFactory;

abstract class ResponseBuilder
{

    function __construct(RequestMethodResponseBuilderFactory $requestMethodResponseBuilderFactory) 
    {
        $this->requestMethodResponseBuilderFactory = $requestMethodResponseBuilderFactory;
    }

    abstract public function setValidatedMetaData($validatedMetaData);
    abstract public function setResponseData($queryResult);
    abstract public function make();
}