<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

abstract class ResourceParameterInfoProvider
{
    protected $apiDataType;
    protected $parameterName;
    protected $parameterAttributeArray;
    protected $parameterDataType;
    protected $defaultValidationRules = [];
    protected $formData = [];

    public function getData(array $parameterAttributeArray, array $resourceFormData): array
    {
        $this->parameterName = $parameterAttributeArray['field'];
        $this->parameterAttributeArray = $parameterAttributeArray;
        $this->parameterDataType = $parameterAttributeArray['type'];
        
        $this->getParameterData();
        $this->isParameterRequired();
        
        $this->mergeResourceParameterFormData($resourceFormData);

        return [
            'apiDataType' => $this->getApiDataType(),
            'defaultValidationRules' => $this->defaultValidationRules,
            'formData' => $this->formData,
        ];
    }

    abstract protected function getParameterData(); // needs to set $this->apiDataType : string, $this->defaultValidationRules : array, $this->formData : array

    protected function isParameterRequired()
    {
        if (
            $this->parameterAttributeArray['null'] == 'no' &&
            $this->parameterAttributeArray['default'] == null &&
            $this->parameterAttributeArray['extra'] != 'auto_increment'
        ) {
            $this->formData['required'] = true;
            $this->defaultValidationRules[] = 'required';
        }
    }

    protected function mergeResourceParameterFormData($resourceFormData)
    {
        if ($resourceFormData && isset($resourceFormData[$this->parameterName])) {
            $this->formData = array_merge($this->formData, $resourceFormData[$this->parameterName]);
        }
    }

    protected function getApiDataType(): string
    {
        if ($this->apiDataTypeIsNotSet()) {
            throw new \Exception('No apiDataType class property set, this must be set in the child class');
        }

        return $this->apiDataType;
    }

    protected function apiDataTypeIsNotSet(): string
    {
        return !$this->apiDataType;
    }
}