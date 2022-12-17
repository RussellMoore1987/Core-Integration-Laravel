<?php

namespace App\CoreIntegrationApi;

use Illuminate\Http\Request;

abstract class RequestDataPrepper
    {
        protected $request;
        protected $preppedData;

        public function __construct(Request $request)
        {
            $this->request = $request;
        }

        public function prep() : void
        {
            $this->preppedData = $this->prepRequestData();
        }

        abstract public function prepRequestData() : array; // Rest = request, Context = array of requests

        public function getPreppedData() : array
        {
            return $this->preppedData;
        }
    }