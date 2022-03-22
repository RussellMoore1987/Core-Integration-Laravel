<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class ContextRequestDataPrepper extends RequestDataPrepper
{

    protected $requests;
    protected $requestName;
    protected $requestData;

    public function prepRequestData()
    {
        $this->prepDefaultData();
        $this->prepRequests();

        // dd($this->request->contextInstructions, $this->preppedData);

        return $this->preppedData;
    }

    protected function prepDefaultData()
    {
        // TODO: check is json
        // if array as well
        $this->requests = $this->request->contextInstructions ? json_decode($this->request->contextInstructions, true) : [];
        $this->preppedData['contextMainError'] = false;
        $this->preppedData['rejectedContextRequest'] = [];
    }

    protected function prepRequests()
    {
        if (is_array($this->requests)) {
            foreach ($this->requests as $requestName => $requestData) {
                // TODO: don't care if asoshet array
                if (!is_numeric($requestName)) { 
                    $this->requestName = $requestName;
                    $this->requestData = $requestData;

                    $this->setEndpointDetails();
                    $this->setParameters();
                } else {
                    $this->preppedData['rejectedContextRequest'][$requestName] = $requestData;
                }
            }
        } else {
            $this->preppedData['contextMainError'] = true;
        }
    }

    protected function setEndpointDetails()
    {
        // TODO: get requestName or requestName from alias 
        $this->preppedData['requests'][$this->requestName]['endpoint'] = $this->requestData['endpoint'] ?? 'index';
        

        $this->preppedData['requests'][$this->requestName]['endpointId'] = $this->requestData['endpointId'] ?? $this->requestData['id'] ?? '';
    }

    protected function setParameters()
    {
        // https://laravel.com/docs/8.x/collections#the-enumerable-contract ???
        unset($this->requestData['endpointId'], $this->requestData['id'], $this->requestData['endpoint']);
        $this->preppedData['requests'][$this->requestName]['parameters'] = $this->requestData;
    }
}