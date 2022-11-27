<?php

namespace App\Http\Controllers;

use App\CoreIntegrationApi\CILRequestRouter;
use Illuminate\Support\Facades\App;

class CILApiController extends Controller
{
    function __construct() 
    {
        $this->CILRequestRouter = App::make(CILRequestRouter::class);
    }

    public function processRequest(){
        return $this->CILRequestRouter->processRequest();
    }

    public function processRestRequest(){
        return $this->CILRequestRouter->processRestRequest();
    }

    public function processContextRequest(){
        return $this->CILRequestRouter->processContextRequest();
    }
}

// TODO-TEST: full end-to-end testing for each route
// processRequest
// processRestRequest
// processContextRequest
