<?php

namespace App\CoreIntegrationApi;

use Illuminate\Http\Request;

abstract class RequestDataPrepper
    {
        protected $request;

        function __construct(Request $request) 
        {
            $this->request = $request;
        }  

        abstract public function prep();

        public function getPreppedData() 
        {
            return $this->request;
        }
    }