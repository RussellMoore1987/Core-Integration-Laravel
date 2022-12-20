<?php

namespace App\Http\Controllers;

use App\CoreIntegrationApi\RestApi\RestRequestProcessor;
use App\CoreIntegrationApi\ContextApi\ContextRequestProcessor;
use Illuminate\Http\JsonResponse;

class CILApiController extends Controller
{
    public function __construct(RestRequestProcessor $restRequestProcessor, ContextRequestProcessor $contextRequestProcessor)
    {
        $this->restRequestProcessor = $restRequestProcessor;
        $this->contextRequestProcessor = $contextRequestProcessor;
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
// processRestRequest
// processContextRequest
