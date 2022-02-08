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
            $this->acceptedClasses = config('coreintegration.acceptedclasses') ?? [];
        }  

        public function prep() 
        {
            $this->preppedData = $this->prepRequestData();
        }

        abstract public function prepRequestData();

        public function getPreppedData() 
        {
            return $this->preppedData;
        }
    }