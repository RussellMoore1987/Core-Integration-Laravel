<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestValidator;
use App\CoreIntegrationApi\QueryResolver;
use App\CoreIntegrationApi\ResponseBuilder;

abstract class RequestProcessor
{
    protected $requestValidator;
    protected $queryResolver;
    protected $responseBuilder;

    function __construct(RequestValidator $requestValidator, QueryResolver $queryResolver, ResponseBuilder $responseBuilder) 
    {
        $this->requestValidator = $requestValidator;
        $this->queryResolver = $queryResolver;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @return The return string is a JSON string
     */
    public function process()
    {
        $this->validate();
        $this->resolve();
        return $this->respond();
    }

    protected function validate() 
    {
        $this->validatedMetaData = $this->requestValidator->validate();
        $this->responseBuilder->setValidatedMetaData($this->validatedMetaData);
    }

    protected function resolve() 
    {
        $queryResult = $this->queryResolver->resolve($this->validatedMetaData);
        $this->responseBuilder->setResponseData($queryResult);
    }

    protected function respond()
    {
        return $this->responseBuilder->make();
    }
}