<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\RequestDataPrepper;

// TODO: fix url case sensitivity, and camelCase and snake_case, Context API as well

class RestRequestDataPrepper extends RequestDataPrepper
{
    public function prepRequestData(): array
    {
        $this->setRequestDetails();
        $this->setEndpointDetails();
        $this->setRequestParameters();

        return $this->preppedData;
    }

    protected function setRequestDetails(): void
    {
        $this->preppedData['url'] = $this->request->url();
        $this->preppedData['requestMethod'] = $this->request->getMethod();
    }

    protected function setEndpointDetails(): void
    {
        $this->preppedData['resource'] = $this->request->resource ?? 'index';
        $this->preppedData['resourceId'] = $this->request->resourceId ?? $this->request->resource_id ?? $this->request->id ?? '';
    }

    protected function setRequestParameters(): void
    {
        $this->preppedData['parameters'] = $this->request->except(['id', 'resource', 'resourceId', 'resource_id']);
    }
}
