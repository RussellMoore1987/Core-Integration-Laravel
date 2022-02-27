<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class RestRequestDataPrepper extends RequestDataPrepper
{ 
    public function prepRequestData()
    {
        $this->setEndpointDetails();
        $this->setMethodCalls();
        $this->setIncludes();
        $this->setPerPageParameter();
        $this->setOrderByParameters();
        $this->setSelectParameters();
        $this->setOtherParameters();

        return $this->preppedData;
    }

    private function setEndpointDetails()
    {
        $this->preppedData['endpoint'] = $this->request->endpoint ?? 'index';
        

        $this->preppedData['endpointId'] = $this->request->id ?? $this->request->endpointId ?? '';
    }

    private function setMethodCalls()
    {
        $this->preppedData['parameters']['methodCalls'] = $this->request->methodCalls ?? [];
    }

    private function setIncludes()
    {
        $this->preppedData['parameters']['includes'] = $this->request->includes ?? [];
    }

    private function setPerPageParameter()
    {
        $this->preppedData['parameters']['perPageParameter'] = $this->request->perPage ?? 30;
    }

    private function setOrderByParameters()
    {
        $this->preppedData['parameters']['orderByParameters'] = $this->request->orderBy ?? [];
    }

    private function setSelectParameters()
    {
        $this->preppedData['parameters']['selectParameters'] = $this->request->columns ?? [];
    }

    private function setOtherParameters()
    {

        $otherParameters = $this->request->except(['id', 'perPage', 'page', 'orderBy', 'columns', 'methodCalls','includes']);
        $this->preppedData['parameters']['otherParameters'] = $otherParameters ?? [];
    }
}