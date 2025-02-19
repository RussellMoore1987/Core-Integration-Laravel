<?php

namespace App\Http\Controllers;

use App\CoreIntegrationApi\RestApi\RestRequestProcessor;
use App\CoreIntegrationApi\ContextApi\ContextRequestProcessor;
use Illuminate\Http\JsonResponse;

class ApiCilController extends Controller
{
    protected $restRequestProcessor;
    protected $contextRequestProcessor;

    public function __construct(RestRequestProcessor $restRequestProcessor, ContextRequestProcessor $contextRequestProcessor)
    {
        $this->restRequestProcessor = $restRequestProcessor;
        $this->contextRequestProcessor = $contextRequestProcessor;
    }

    public function processRestRequest(): JsonResponse
    {
        return $this->restRequestProcessor->process();
    }

    // TODO: need to test when ready, rest path first
    public function processContextRequest(): JsonResponse
    {
        // return ["Message" => "Got Here!!! " . request()->contextInstructions]; // TODO: needs to be removed
        return $this->contextRequestProcessor->process(); // TODO: validate, must be post request
    }
}

// TODO-TEST: full end-to-end testing for each route
// processRestRequest
// processContextRequest
