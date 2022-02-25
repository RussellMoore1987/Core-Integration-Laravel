<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class RestRequestDataPrepper extends RequestDataPrepper
{ 
    private $allAvailableParameters;

    public function prepRequestData()
    {
        $this->setClass();
        $this->setEndpointDetails();
        $this->setMethodCalls();
        $this->setIncludes();
        $this->setPerPageParameter();
        $this->setOrderByParameters();
        $this->setSelectParameters();
        $this->setOtherParameters();
    }

    // TODO: might Use these functions for both rest and context API
    private function setClass()
    {
        if ($this->request->endpoint && isset($this->acceptedClasses[$this->request->endpoint])) {
            $this->preppedData['class'] = $this->acceptedClasses[$this->request->endpoint];
        } else {
            $this->preppedData['class'] = NULL;
        }
    }

    // TODO: set ids
    // TODO: set id as class id, dynamically find it
    // $class->getKeyName()
    
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