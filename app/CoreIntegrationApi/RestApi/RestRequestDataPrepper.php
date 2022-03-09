<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class RestRequestDataPrepper extends RequestDataPrepper
{ 
    public function prepRequestData()
    {
        $this->setEndpointDetails();
        $this->setParameters();

        return $this->preppedData;
    }

    private function setEndpointDetails()
    {
        $this->preppedData['endpoint'] = $this->request->endpoint ?? 'index';
        

        $this->preppedData['endpointId'] = $this->request->endpointId ?? $this->request->id ?? '';
    }

    private function setParameters()
    {
        $this->preppedData['parameters'] = $this->request->except(['id']);
    }
}