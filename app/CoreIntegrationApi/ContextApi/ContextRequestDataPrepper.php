<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class ContextRequestDataPrepper extends RequestDataPrepper
{
    protected $requests;
    protected $requestName;
    protected $endpoint;
    protected $requestData;

    public function prepRequestData()
    {
        $this->prepDefaultData();
        $this->prepRequests();

        return $this->preppedData;
    }

    protected function prepDefaultData()
    {
        $this->preppedData['contextErrorInstructions'] = true;
        $this->preppedData['contextErrorNotJson'] = true;
        $this->preppedData['requests'] = [];
        $this->requests = [];
        
        if ($this->request->contextInstructions) {
            if ($this->isJson($this->request->contextInstructions)) {
                $this->requests = json_decode($this->request->contextInstructions, true);
                $this->preppedData['contextErrorNotJson'] = false;
            }
            $this->preppedData['contextErrorInstructions'] = false;
        }
    }

    protected function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    protected function prepRequests()
    {
        if (is_array($this->requests)) {
            foreach ($this->requests as $requestName => $requestData) {
                $this->requestName = $requestName;
                $this->endpoint = $requestName;
                $this->requestData = $requestData;

                $this->checkSplitRequestName();
                $this->setEndpointDetails();
                $this->setParameters();
            }
        }
    }

    protected function checkSplitRequestName()
    {
        if (str_contains($this->requestName, '::')) {
            $requestNameArray = explode('::', $this->requestName);
            $this->requestName = $requestNameArray[0];
            $this->endpoint = $requestNameArray[1];
        }
    }

    protected function setEndpointDetails()
    {
        $this->preppedData['requests'][$this->requestName]['endpoint'] = $this->requestData['endpoint'] ?? $this->endpoint ?? 'index';

        $this->preppedData['requests'][$this->requestName]['endpointId'] = $this->requestData['endpoint_id'] ?? $this->requestData['endpointId'] ?? $this->requestData['id'] ?? '';
    }

    protected function setParameters()
    {
        $this->unsetDefaultParameters();
        $this->preppedData['requests'][$this->requestName]['parameters'] = $this->requestData;
    }

    protected function unsetDefaultParameters()
    {
        
        if ($this->checkIfWeNeedToUnset()) {
            unset(
                $this->requestData['endpointId'], 
                $this->requestData['endpoint_id'], 
                $this->requestData['id'], 
                $this->requestData['endpoint']
            );
        }
    }

    protected function checkIfWeNeedToUnset()
    {
        if (is_array($this->requestData)) {
            $keys = array_keys($this->requestData);
            $KeysToUnset = ['endpointId', 'endpoint_id', 'id', 'endpoint'];

            foreach ($KeysToUnset as $key) {
                if (in_array($key, $keys)) { return true; }
            }
        }
        return false;
    }
}