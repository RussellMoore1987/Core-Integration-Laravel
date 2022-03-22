<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\RequestDataPrepper;

class ContextRequestDataPrepper extends RequestDataPrepper
{

    // TODO: walk through code and make sure it has all tests
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
        if ($this->request->contextInstructions) {
            if ($this->isJson($this->request->contextInstructions)) {
                $this->requests = json_decode($this->request->contextInstructions, true);
                $this->preppedData['contextErrorNotJson'] = false;
            } else {
                $this->requests = [];
                $this->preppedData['contextErrorNotJson'] = true;
            }
            $this->preppedData['contextMainError_instructions'] = false;
        } else {
            $this->preppedData['contextMainError_instructions'] = true;
            $this->preppedData['contextErrorNotJson'] = true;
        }
        $this->preppedData['requests'] = [];
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
        if (!is_numeric($this->endpoint)) {
            $this->preppedData['requests'][$this->requestName]['endpoint'] = $this->endpoint ?? $this->requestData['endpoint'] ?? 'index';
        } else {
            $this->preppedData['requests'][$this->requestName]['endpoint'] = $this->requestData['endpoint'] ?? 'index';
        }

        $this->preppedData['requests'][$this->requestName]['endpointId'] = $this->requestData['endpoint_id'] ?? $this->requestData['endpointId'] ?? $this->requestData['id'] ?? '';
    }

    protected function setParameters()
    {
        unset(
            $this->requestData['endpointId'], 
            $this->requestData['endpoint_id'], 
            $this->requestData['id'], 
            $this->requestData['endpoint']
        );
        $this->preppedData['requests'][$this->requestName]['parameters'] = $this->requestData;
    }
}