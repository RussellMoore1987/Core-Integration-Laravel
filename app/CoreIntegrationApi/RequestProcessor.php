<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestValidator;
use App\CoreIntegrationApi\QueryResolver;
use App\CoreIntegrationApi\ResponseBuilder;
use Illuminate\Http\JsonResponse;

abstract class RequestProcessor
{
    protected RequestValidator $requestValidator;
    protected QueryResolver $queryResolver;
    protected ResponseBuilder $responseBuilder;
    protected array $validatedMetaData;

    public function __construct(RequestValidator $requestValidator, QueryResolver $queryResolver, ResponseBuilder $responseBuilder)
    {
        $this->requestValidator = $requestValidator;
        $this->queryResolver = $queryResolver;
        $this->responseBuilder = $responseBuilder;
    }

    public function process(): JsonResponse
    {
        $this->validateRequest();
        $this->getRequestedData();
        return $this->respond();
    }

    protected function validateRequest(): void
    {
        $this->validatedMetaData = $this->requestValidator->validate();
        $this->responseBuilder->setValidatedMetaData($this->validatedMetaData);
        // * if validation fails request will be sent back to the user as a HttpResponseException (a 404, 422 response)
    }

    protected function getRequestedData(): void
    {
        $queryResult = $this->queryResolver->resolve($this->validatedMetaData);
        $this->responseBuilder->setResponseData($queryResult);
    }

    protected function respond(): JsonResponse
    {
        return $this->responseBuilder->make();
    }
}
