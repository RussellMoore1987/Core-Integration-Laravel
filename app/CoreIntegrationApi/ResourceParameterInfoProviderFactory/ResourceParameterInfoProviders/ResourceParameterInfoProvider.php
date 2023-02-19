<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\Exceptions\ResourceParameterInfoProviderException;

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
        
        $this->setParameterData();
        $this->isParameterRequiredThenSetParameterInfoAsRequired();
        
        $this->mergeResourceParameterFormData($resourceFormData);
        
        $this->validateRequiredChildClassConstruction();

        return [
            'apiDataType' => $this->apiDataType,
            'defaultValidationRules' => $this->defaultValidationRules,
            'formData' => $this->formData,
        ];
    }

    abstract protected function setParameterData(): void; // see requirements below
    // child class needs to set
    // $this->apiDataType : string
    // $this->defaultValidationRules : array
    // $this->formData : array
    // see example IntResourceParameterInfoProvider

    protected function isParameterRequiredThenSetParameterInfoAsRequired()
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

    protected function validateRequiredChildClassConstruction(): void
    {
        if (!$this->apiDataType) {
            $this->throwException('apiDataType', 100);
        }
        if (!$this->defaultValidationRules) {
            $this->throwException('defaultValidationRules', 101);
        }
        if (!$this->formData) {
            $this->throwException('formData', 102);
        }
    }

    protected function throwException(string $attribute, int $code): void
    {
        throw new ResourceParameterInfoProviderException("The class attribute {$attribute} must be set in the child class \"" . get_class($this) . '".', $code);
    }
}
