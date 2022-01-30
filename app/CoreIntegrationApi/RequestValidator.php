<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\RequestDataPrepper;

abstract class RequestValidator 
    {

        protected $requestDataPrepper;
        protected $acceptableParameters;
        protected $class;
        protected $endpoint;
        protected $errors = [];
        protected $validatedMetaData;
        
        
        protected $rejectedParameters = [];
        protected $acceptedParameters = [];
        protected $queryArguments = [];

        function __construct(RequestDataPrepper $requestDataPrepper) 
        {
            $this->requestDataPrepper = $requestDataPrepper;
        }   

        public function validate()
        {
            $this->requestDataPrepper->prep();

            $this->validateRequest($this->requestDataPrepper->getPreppedData());

            return $this->validatedMetaData;
        }

        protected function validateRequest($request)
        {
            $this->setUpPreppedRequest($request);
            $this->getAcceptableParameters();

            $this->validateEndPoint();
            $this->validateMethodCalls();
            $this->validateIncludes();
            $this->validatePerPageParameter();
            $this->validateOrderByParameter();
            $this->validateSelectParameter();
            $this->validateAllOtherParameter();
            $this->setValidatedMetaData();
        }

        protected function setUpPreppedRequest($request)
        {
            $this->class = $request['class'];
            $this->endpoint = $request['endpoint'];
            $this->methodCalls = $request['methodCalls'] ?? [];
            $this->includes = $request['includes'] ?? [];
            $this->perPageParameter = $request['perPageParameter'] ?? 30;
            $this->orderByParameters = $request['orderByParameters'] ?? [];
            $this->selectParameters = $request['selectParameters'] ?? [];
            $this->otherParameters = $request['otherParameters'] ?? [];
        }

        // TODO: Search for field that's not displayed *hidden, kinda like a Social Security number
        protected function getAcceptableParameters()
        {
            // set $this->acceptableParameters
        }

        protected function validateEndPoint()
        {
            // see if end point is in config('coreintegration.acceptedclasses')
        }

        protected function validateMethodCalls()
        {
            // class have method
        }

        protected function validateIncludes()
        {
            // class have Includes
        }

        protected function validatePerPageParameter()
        {
            // code...
        }

        protected function validateOrderByParameter()
        {
            // code...
            // use $this->acceptableParameters
        }

        protected function validateSelectParameter()
        {
            // code...
            // use $this->acceptableParameters
        }

        protected function validateAllOtherParameter()
        {
            // code...
            // use $this->acceptableParameters
            // Run them through a data preper or Parameter validator
            // All parameter validation needs to be done here
        }

        protected function setValidatedMetaData()
        {
            $validatedRequestMetaData['rejectedParameters'] = $this->getRejectedParameters();
            $validatedRequestMetaData['acceptedParameters'] = $this->getAcceptedParameters();
            $validatedRequestMetaData['errors'] = $this->errors;
            $validatedRequestMetaData['queryArguments'] = $this->getQueryArguments();
            $this->validatedMetaData = $validatedRequestMetaData;
        }

        protected function setRejectedParameter()
        {

        }
        public function getRejectedParameters()
        {

        }
        
        protected function setAcceptedParameter()
        {

        }
        public function getAcceptedParameters()
        {

        }

        protected function setQueryArgument()
        {

        }
        public function getQueryArguments()
        {

        }

        public function getValidatedQueryData()
        {

        }
    }