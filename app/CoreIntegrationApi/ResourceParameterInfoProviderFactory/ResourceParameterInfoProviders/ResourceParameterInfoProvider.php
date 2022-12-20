<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

abstract class ResourceParameterInfoProvider
{
    protected $apiDataType;
    protected $defaultValidationRules = [];
    protected $formData = [];

    public function getData(array $parameterDataInfo,  array $parameterClassFormData) : array
    {
        $this->parameterName = $parameterDataInfo['field'];
        $this->parameterDataInfo = $parameterDataInfo;
        $this->dataType = $parameterDataInfo['type'];
        
        $this->getFormData();
        $this->checkToSeeIfFormDataIsRequired();
        
        $this->checkForClassParameterFormData($parameterClassFormData);

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

    protected function checkForClassParameterFormData($parameterClassFormData)
    {
        if ($parameterClassFormData && isset($parameterClassFormData[$this->parameterName])) {
            $this->formData = array_merge($this->formData, $parameterClassFormData[$this->parameterName]);
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