<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use Illuminate\Database\Eloquent\Model;

abstract class ParameterDataProvider 
{
    protected $apiDataType;
    protected $defaultValidationRules = [];
    protected $formData = []; 

    protected function getApiDataType() : string
    {
        if (!$this->apiDataType) {
            throw new \Exception('No apiDataType class property set, this must be set in the child class');
        }

        return $this->apiDataType;
    }

    public function getData($dataType, $parameterName, Model $parameterClass) : array
    {
        $this->dataType = $dataType;
        $this->parameterClass = $parameterClass;
        $this->parameterName = $parameterName;

        $this->getFormData();

        $this->checkForClassParameterFormData();

        return [
            'apiDataType' => $this->getApiDataType(),
            'defaultValidationRules' => $this->defaultValidationRules,
            'formData' => $this->formData,
        ];
    }

    abstract protected function getFormData(); // needs to set $this->apiDataType, $this->defaultValidationRules, $this->formData  : array

    protected function checkForClassParameterFormData() // merge arrays
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