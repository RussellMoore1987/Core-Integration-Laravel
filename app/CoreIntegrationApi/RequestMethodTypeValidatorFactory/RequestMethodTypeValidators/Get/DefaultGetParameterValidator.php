<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\Get;

use App\CoreIntegrationApi\ValidatorDataCollector;

class DefaultGetParameterValidator
{
    const DEFAULT_GET_PARAMETERS = [
        'per_page', 'perpage',
        'page',
        'column_data', 'columndata',
        'form_data', 'formdata',
        'data_only', 'dataonly',
        'full_info', 'fullinfo',
    ];

    protected $parameterType;
    protected $parameterName;
    protected $parameterValue;
    protected $validatorDataCollector;

    public function validate($parameterName, $parameterValue, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->parameterType = null; // this is set for resetting purposes
        $this->parameterName = $parameterName;
        $this->parameterValue = $parameterValue;
        $this->validatorDataCollector = $validatorDataCollector;

        $this->isPerPageParameterThenValidate();
        $this->isPageParameterThenValidate();
        $this->isColumnDataParameterThenSet();
        $this->isFormDataParameterThenSet();
        $this->isDataOnlyParameterThenSet();
        $this->isFullInfoParameterThenSet();
    }

    protected function isPerPageParameterThenValidate(): void
    {
        if ($this->parameterTypeIsNotSet() && in_array($this->parameterName, ['perpage', 'per_page'])) {
            $this->parameterType = true;
            
            if ($this->isInt($this->parameterValue)) {
                $this->validatorDataCollector->setAcceptedParameters([
                    'perPage' => (int) $this->parameterValue
                ]);
            } else {
                $this->validatorDataCollector->setRejectedParameters([
                    'perPage' => [
                        'value' => $this->parameterValue,
                        'parameterError' => 'This parameter\'s value must be an int.'
                    ]
                ]);
            }
        }
    }
    
    protected function isPageParameterThenValidate(): void
    {
        if ($this->parameterTypeIsNotSet() && $this->parameterName == 'page') {
            $this->parameterType = true;
            
            if ($this->isInt($this->parameterValue)) {
                $this->validatorDataCollector->setAcceptedParameters([
                    'page' => (int) $this->parameterValue
                ]);
            } else {
                $this->validatorDataCollector->setRejectedParameters([
                    'page' => [
                        'value' => $this->parameterValue,
                        'parameterError' => 'This parameter\'s value must be an int.'
                    ]
                ]);
            }
        }
    }

    protected function isColumnDataParameterThenSet(): void
    {
        if ($this->parameterTypeIsNotSet() && in_array($this->parameterName, ['columndata', 'column_data'])) {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'columnData' => [
                    'value' => $this->parameterValue,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it will high jack the request and only return parameter data for this resource/endpoint'
                ]
            ]);
        }
    }

    protected function isFormDataParameterThenSet(): void
    {
        if ($this->parameterTypeIsNotSet() && in_array($this->parameterName, ['formdata', 'form_data'])) {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'formData' => [
                    'value' => $this->parameterValue,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it will high jack the request and only return parameter form data for this resource/endpoint'
                ]
            ]);
        }
    }

    protected function isDataOnlyParameterThenSet(): void
    {
        if ($this->parameterTypeIsNotSet() && in_array($this->parameterName, ['dataonly', 'data_only'])) {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'dataOnly' => [
                    'value' => $this->parameterValue,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it will high jack the request and only return the resource/endpoint data'
                ]
            ]);
        }
    }

    protected function isFullInfoParameterThenSet(): void
    {
        if ($this->parameterTypeIsNotSet() && in_array($this->parameterName, ['fullinfo', 'full_info'])) {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'fullInfo' => [
                    'value' => $this->parameterValue,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it will high jack the request and will return all resource/endpoint information'
                ]
            ]);
        }
    }
    protected function parameterTypeIsNotSet(): bool
    {
        return !$this->parameterType;
    }

    protected function isInt($value): bool
    {
        return is_numeric($value) && !str_contains($value, '.');
    }
}
