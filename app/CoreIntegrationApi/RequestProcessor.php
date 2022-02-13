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
    public function process() : string
    {
        $this->validate();
        $this->resolve();
        return $this->respond();
    }

    protected function validate() 
    {
        $metaData = $this->requestValidator->validate();
        $this->responseBuilder->setValidationMetaData($metaData);
    }

    protected function resolve() 
    {
        $validatedQueryData = $this->requestValidator->getValidatedQueryData();
        $queryResult = $this->queryResolver->resolve($validatedQueryData);
        $this->responseBuilder->setResponseData($queryResult);
    }

    protected function respond()
    {
        return $this->responseBuilder->make();
    }
}