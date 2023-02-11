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
        $this->isParameterRequired();
        
        $this->mergeResourceParameterFormData($resourceFormData);
        
        $this->validateRequiredChildClassConstruction();

        return [
            'apiDataType' => $this->apiDataType,
            'defaultValidationRules' => $this->defaultValidationRules,
            'formData' => $this->formData,
        ];
    }

    abstract protected function setParameterData(): void; // see requirements below
    // child class needs to set $this->apiDataType : string
    // $this->defaultValidationRules : array
    // $this->formData : array
    // see example IntResourceParameterInfoProvider.php

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

    protected function validateRequiredChildClassConstruction(): void
    {
        if (!$this->apiDataType) {
            $this->throwException('The class attribute apiDataType must be set in the child class "' . get_class($this) . '".', 100);
        }
        if (!$this->defaultValidationRules) {
            $this->throwException('The class attribute defaultValidationRules must be set in the child class "' . get_class($this) . '".', 101);
        }
        if (!$this->formData) {
            $this->throwException('The class attribute formData must be set in the child class "' . get_class($this) . '".', 102);
        }
    }

    protected function throwException(string $message, int $code): void
    {
        throw new ResourceParameterInfoProviderException($message, $code);
    }
}
