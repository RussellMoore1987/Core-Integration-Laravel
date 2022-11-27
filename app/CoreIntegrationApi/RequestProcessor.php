<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestValidator;
use App\CoreIntegrationApi\QueryResolver;
use App\CoreIntegrationApi\ResponseBuilder;
use Illuminate\Http\JsonResponse;

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

    public function process() : JsonResponse
    {
        $this->validate();
        $this->resolve();
        return $this->respond();
    }

    protected function validate() : void
    {
        $this->validatedMetaData = $this->requestValidator->validate();
        $this->responseBuilder->setValidatedMetaData($this->validatedMetaData);
    }

    protected function resolve() : void
    {
        $queryResult = $this->queryResolver->resolve($this->validatedMetaData);
        $this->responseBuilder->setResponseData($queryResult);
    }

    protected function respond() : JsonResponse
    {
        return $this->responseBuilder->make();
    }
}