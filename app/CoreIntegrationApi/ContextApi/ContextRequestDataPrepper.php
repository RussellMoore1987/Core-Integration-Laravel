<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class ContextRequestDataPrepper extends RequestDataPrepper
{
    protected $requests;
    protected $requestName;
    protected $resource;
    protected $requestData;

    // TODO: add request information like URL and HTTP method
    public function prepRequestData() : array
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
                $this->resource = $requestName;
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
            $this->resource = $requestNameArray[1];
        }
    }

    protected function setEndpointDetails()
    {
        $this->preppedData['requests'][$this->requestName]['resource'] = $this->requestData['resource'] ?? $this->resource ?? 'index';

        $this->preppedData['requests'][$this->requestName]['resourceId'] = $this->requestData['resource_id'] ?? $this->requestData['resourceId'] ?? $this->requestData['id'] ?? '';
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
                $this->requestData['resourceId'],
                $this->requestData['resource_id'],
                $this->requestData['id'],
                $this->requestData['resource']
            );
        }
    }

    protected function checkIfWeNeedToUnset()
    {
        if (is_array($this->requestData)) {
            $keys = array_keys($this->requestData);
            $keysToUnset = ['resourceId', 'resource_id', 'id', 'resource'];

            foreach ($keysToUnset as $key) {
                if (in_array($key, $keys)) { return true; }
            }
        }
        return false;
    }
}