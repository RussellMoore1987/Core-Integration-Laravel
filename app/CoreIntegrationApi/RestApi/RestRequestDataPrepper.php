<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class RestRequestDataPrepper extends RequestDataPrepper
{ 
    private $allAvailableParameters;

    public function prepRequestData()
    {
        $this->allAvailableParameters = $this->request->input();
        
        // ! prep this data ************************************************ setEndpointDetails()
        // set class
        $this->setClass();
        $this->setEndpointDetails();
        $this->setMethodCalls();
        $this->setIncludes();
        
        
        $this->setPerPageParameter();
        $this->setOrderByParameters();
        $this->setSelectParameters();
        $this->setOtherParameters();
        
        // ! $request->except(['key1','key2',....])
        // if there remove from all parameters
        

        dd($this->preppedData, 'got here@@@');
        // dd($this->preppedData, $this->request->input(), request()->all(), 'got here@@@');

        $this->preppedData['includes'] ?? [];
        $this->preppedData['perPageParameter'] ?? 30;
        $this->preppedData['orderByParameters'] ?? [];
        $this->preppedData['selectParameters'] ?? [];
        $this->preppedData['otherParameters'] ?? [];

        // Initial set up of key variables
        $this->endpointKey = $endpointKey;
        $this->classId = $classId;
        $this->acceptedClasses = config('coreintegration.acceptedclasses');
        $this->indexUrlPath = $endpointKey !== NULL ? substr($request->url(), 0, strpos($request->url(), $this->endpointKey)) : $request->url();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? request()->method() ?? null;
        $this->url = $request->url();
        

        // $this->class = $request['class'];
        //     $this->endpoint = $request['endpoint'];
        //     $this->methodCalls = $request['methodCalls'] ?? [];
        //     $this->includes = $request['includes'] ?? [];
        //     $this->perPageParameter = $request['perPageParameter'] ?? 30;
        //     $this->orderByParameter = $request['orderByParameter'] ?? [];
        //     $this->selectParameter = $request['selectParameter'] ?? [];
        //     $this->otherParameter = $request['otherParameter'] ?? [];
        
    }

    private function setClass()
    {
        if ($this->request->endpoint && isset($this->acceptedClasses[$this->request->endpoint])) {
            $this->preppedData['class'] = $this->acceptedClasses[$this->request->endpoint];
        } else {
            $this->preppedData['class'] = NULL;
        }
    }

    private function setEndpointDetails()
    {
        $this->preppedData['endpoint'] = $this->request->endpoint ?? 'index';
        

        $this->preppedData['endpointId'] = $this->request->id ?? $this->request->endpointId ?? '';
    }

    private function setMethodCalls()
    {
        $this->preppedData['methodCalls'] = $this->request->methodCalls ?? [];
    }

    private function setIncludes()
    {
        $this->preppedData['includes'] = $this->request->includes ?? [];
    }

    private function setPerPageParameter()
    {
        $this->preppedData['perPageParameter'] = $this->request->perPage ?? 30;
    }

    private function setOrderByParameters()
    {
        $this->preppedData['orderByParameters'] = $this->request->orderBy ?? [];
    }

    private function setSelectParameters()
    {
        $this->preppedData['selectParameters'] = $this->request->columns ?? [];
    }

    private function setOtherParameters()
    {
        $otherParameters = $this->request->except(['id', 'perPage', 'orderBy', 'columns', 'methodCalls','includes']);
        $this->preppedData['otherParameters'] = $otherParameters ?? [];
    }
}