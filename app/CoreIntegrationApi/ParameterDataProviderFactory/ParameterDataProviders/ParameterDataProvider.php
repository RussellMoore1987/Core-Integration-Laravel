<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use Illuminate\Database\Eloquent\Model;

abstract class ParameterDataProvider 
{
    protected $apiDataType;
    protected $formData = []; 

    public function getApiDataType() : string
    {
        if (!$this->apiDataType) {
            throw new \Exception('No apiDataType class property set, must be set in the child class');
        }

        return $this->apiDataType;
    }

    public function getData($dataType, $parameterName, Model $parameterClass) : array
    {
        $this->dataType = $dataType;
        $this->parameterClass = $parameterClass;
        $this->parameterName = $parameterName;

        $this->getFormData();

        $this->checkForClassFormData();

        return [
            'apiDataType' => $this->getApiDataType(),
            'formData' => $this->formData,
        ];
    }

    abstract protected function getFormData(); // needs to set $this->formData : array

    protected function checkForClassFormData()
    {
        if (
            is_array($this->formData) && 
            $this->parameterClass->formData && 
            isset($this->parameterClass->formData[$this->parameterName])
            ) {
            $this->formData = array_merge($this->formData, $this->parameterClass->formData[$this->parameterName]);
        }
    }

}