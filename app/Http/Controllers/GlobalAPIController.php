<?php

namespace App\Http\Controllers;

use App\CoreIntegrationApi\RequestProcessRouter;
use Illuminate\Support\Facades\App;

class GlobalAPIController extends Controller
{
    function __construct() 
    {
        $this->requestProcessRouter = App::make(RequestProcessRouter::class);
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
