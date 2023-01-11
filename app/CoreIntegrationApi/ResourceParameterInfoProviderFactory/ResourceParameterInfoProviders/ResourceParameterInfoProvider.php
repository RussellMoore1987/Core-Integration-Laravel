<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (I have a stash of tests**** EndpointValidatorTest.php) (sub IntResourceParameterInfoProvider DateResourceParameterInfoProvider)
// [] read over
// [] test groups, rest, context
// [] add return type : void
// [] testing what I need to test

abstract class ResourceParameterInfoProvider
{
    protected $apiDataType;
    protected $parameterName;
    protected $parameterAttributeArray;
    protected $parameterDataType;
    protected $defaultValidationRules = [];
    protected $formData = [];

    public function getData(array $parameterAttributeArray, array $resourceFormData) : array
    {
        $this->parameterName = $parameterAttributeArray['field'];
        $this->parameterAttributeArray = $parameterAttributeArray;
        $this->parameterDataType = $parameterAttributeArray['type'];
        
        $this->getFormData(); // TODO: rename ??? getParameterInfo*** getParameterData
        $this->isParameterRequired();
        
        $this->checkForClassParameterFormData($resourceFormData);

        return [
            'apiDataType' => $this->getApiDataType(),
            'defaultValidationRules' => $this->defaultValidationRules,
            'formData' => $this->formData,
        ];
    }

    abstract protected function getFormData(); // needs to set $this->apiDataType : string, $this->defaultValidationRules : array, $this->formData : array

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

    protected function checkForClassParameterFormData($resourceFormData)
    {
        if ($resourceFormData && isset($resourceFormData[$this->parameterName])) {
            $this->formData = array_merge($this->formData, $resourceFormData[$this->parameterName]);
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