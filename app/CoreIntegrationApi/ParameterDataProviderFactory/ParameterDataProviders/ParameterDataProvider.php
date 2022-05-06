<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use Illuminate\Database\Eloquent\Model;

abstract class ParameterDataProvider 
{
    protected $apiDataType;
    protected $validationRules = [];
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
        $this->checkForClassParameterValidationRules();

        return [
            'apiDataType' => $this->getApiDataType(),
            'validationRules' => $this->validationRules,
            'formData' => $this->formData,
        ];
    }

    abstract protected function getFormData(); // needs to set $this->apiDataType, $this->validationRules, $this->formData  : array

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

    protected function checkForClassParameterValidationRules() // replace array
    {
        if ($this->parameterClass->validationRules) {
            $this->validationRules = [
                'modelValidation' => $this->parameterClass->validationRules['modelValidation'][$this->parameterName] ?? [],
                'createValidation' => $this->parameterClass->validationRules['createValidation'][$this->parameterName] ?? [],
            ];
        }
    }
}