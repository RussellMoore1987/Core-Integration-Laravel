<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RestApi\RestRequestProcessor;
use App\CoreIntegrationApi\ContextApi\ContextRequestProcessor;
use Illuminate\Http\JsonResponse;

class CILRequestRouter
{
    private $restRequestProcessor;
    private $contextRequestProcessor;

    public function __construct(RestRequestProcessor $restRequestProcessor, ContextRequestProcessor $contextRequestProcessor)
    {
        $this->restRequestProcessor = $restRequestProcessor;
        $this->contextRequestProcessor = $contextRequestProcessor;
    }
    
    public function processRequest() : JsonResponse
    {
        $request = request();
        if ($request->contextInstructions && $request->method() == 'POST') {
            return $this->processContextRequest();
        } else {
            return $this->processRestRequest();
        }
    }

    public function processRestRequest() : JsonResponse
    {
        return $this->restRequestProcessor->process();
    }

    public function processContextRequest() : JsonResponse
    {
        // return ["Message" => "Got Here!!! " . request()->contextInstructions]; // TODO: needs to be removed
        return $this->contextRequestProcessor->process(); // TODO: validate, must be post request
    }
}

// TODO-TEST: full end-to-end testing for each route
// processRequest
// processRestRequest
// processContextRequest
