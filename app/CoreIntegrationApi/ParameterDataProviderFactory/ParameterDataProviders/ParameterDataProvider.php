<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use Illuminate\Database\Eloquent\Model;

abstract class ParameterDataProvider 
{
    protected $apiDataType;
    protected $defaultValidationRules = [];
    protected $formData = []; 

    public function getData(array $parameterDataInfo,  Model $parameterClass) : array
    {
        $this->parameterName = $parameterDataInfo['field'];
        $this->parameterDataInfo = $parameterDataInfo;
        $this->dataType = $parameterDataInfo['type'];
        $this->parameterClass = $parameterClass;
        
        $this->getFormData();
        $this->checkToSeeIfFormDataIsRequired();
        
        $this->checkForClassParameterFormData();

        return [
            'apiDataType' => $this->getApiDataType(),
            'defaultValidationRules' => $this->defaultValidationRules,
            'formData' => $this->formData,
        ];
    }

    abstract protected function getFormData(); // needs to set $this->apiDataType, $this->defaultValidationRules, $this->formData  : array

    protected function checkToSeeIfFormDataIsRequired()
    {
        if (
            $this->parameterDataInfo['null'] == 'no' && 
            $this->parameterDataInfo['default'] == null &&
            $this->parameterDataInfo['extra'] != 'auto_increment'
        ) {
            $this->formData['required'] = true;
            $this->defaultValidationRules[] = 'required';
        }
    }

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

    protected function getApiDataType() : string
    {
        if (!$this->apiDataType) {
            throw new \Exception('No apiDataType class property set, this must be set in the child class');
        }

        return $this->apiDataType;
    }
}