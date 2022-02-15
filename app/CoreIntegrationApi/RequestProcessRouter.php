<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RestApi\RestRequestProcessor;
use App\CoreIntegrationApi\ContextApi\ContextRequestProcessor;

class RequestProcessRouter
{
    private $restRequestProcessor;
    private $contextRequestProcessor;

    function __construct(RestRequestProcessor $restRequestProcessor, ContextRequestProcessor $contextRequestProcessor) 
    {
        $this->restRequestProcessor = $restRequestProcessor;
        $this->contextRequestProcessor = $contextRequestProcessor;
    }  
    
    public function processRequest()
    {
        if (request()->contextInstructions) {
            return $this->processContextRequest();
        } else {
            return $this->processRestRequest();
        }
    }

    public function processRestRequest() 
    {
        return $this->restRequestProcessor->process();
    }

    public function processContextRequest() 
    {
        return ["Message" => "Got Here!!! " . request()->contextInstructions]; // TODO: needs to be removed
        return $this->contextRequestProcessor->process();
    }
}
