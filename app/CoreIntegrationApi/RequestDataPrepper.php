<?php

namespace App\CoreIntegrationApi;

use Illuminate\Http\Request;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;

abstract class RequestDataPrepper
    {
        protected $request;
        protected $preppedData;

        function __construct(Request $request, ParameterValidatorFactory $parameterValidatorFactory) 
        {
            $this->request = $request;
            $this->acceptedClasses = config('coreintegration.acceptedclasses') ?? [];
            $this->parameterValidatorFactory = $parameterValidatorFactory;
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

    // REST
        // $_GET = [
        //     'select' => 'name,email,phone',
        //     'name' => 'sam'
        // ];
    // Context
        // $contextInstructions = [
        //     'users' => [ // as get
        //         'select' => 'name,email,phone',
        //         'name' => 'sam'
        //     ],
        //     'projects' => [ // as get
        //         'select' => 'title,lead,customer,email,phone',
        //         'title' => 'Gogo!!!'
        //     ],
        //     'projects2::projects' => [ // as get
        //         'select' => 'title,lead,customer,email,phone',
        //         'title' => 'Soso!!!'
        //     ]
        // ];