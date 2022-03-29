<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class RestRequestDataPrepper extends RequestDataPrepper
{ 
    public function prepRequestData()
    {
        $this->setRequestDetails();
        $this->setEndpointDetails();
        $this->setParameters();

        return $this->preppedData;
    }

    private function setRequestDetails()
    {
        $this->preppedData['url'] = $this->request->url();
        $this->preppedData['httpMethod'] = $this->request->getMethod();
    }

    private function setEndpointDetails()
    {
        $this->preppedData['endpoint'] = $this->request->endpoint ?? 'index';

        $this->preppedData['endpointId'] = $this->request->endpointId ?? $this->request->endpoint_id ?? $this->request->id ?? '';
    }

    private function setParameters()
    {
        $this->preppedData['parameters'] = $this->request->except(['id', 'endpoint', 'endpointId', 'endpoint_id']);
    }
}