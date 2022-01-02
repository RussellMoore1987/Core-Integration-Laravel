<?php

namespace App\Http\Controllers;

use App\CoreIntegrationApi\RequestProcessRouter;

class GlobalAPIController extends Controller
{
    function __construct() 
    {
        $this->requestProcessRouter = new RequestProcessRouter;
    }

    public function processRequest(){
        return $this->requestProcessRouter->processRequest();
    }

    public function processRestRequest(){
        return $this->requestProcessRouter->processRestRequest();
    }

    public function processContextRequest(){
        return $this->requestProcessRouter->processContextRequest();
    }
}
