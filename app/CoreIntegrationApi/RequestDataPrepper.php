<?php

namespace App\CoreIntegrationApi;

use Illuminate\Http\Request;

abstract class RequestDataPrepper
    {
        protected $request;
        protected $preppedData;

        function __construct(Request $request) 
        {
            $this->request = $request;
        }  

        public function prep() 
        {
            $this->preppedData = $this->prepRequestData();
        }

        abstract public function prepRequestData(); // Rest = request, Context = array of requests

        public function getPreppedData() 
        {
            return $this->preppedData;
        }
    }