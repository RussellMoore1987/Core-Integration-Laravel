<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators;

use App\CoreIntegrationApi\ValidatorDataCollector;

// TODO: make a test for this class
class DefaultGetParameterValidator
{
    protected $key;
    protected $value;
    protected $validatorDataCollector;
    protected $parameterType;

    public function validate($key, $value, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->parameterType = null; // this is set for resetting purposes
        $this->key = $key;
        $this->value = $value;
        $this->validatorDataCollector = $validatorDataCollector;

        $this->isPerPageParameterThenValidate();
        $this->isPageParameterThenValidate();
        $this->isColumnDataParameterThenValidate();
        $this->isFormDataParameterThenValidate();
    }

    protected function isPerPageParameterThenValidate(): void
    {
        if ($this->parameterTypeIsNotSet() && in_array($this->key, ['perpage', 'per_page'])) {
            $this->parameterType = true;
            
            if ($this->isInt($this->value)) {
                $this->validatorDataCollector->setAcceptedParameters([
                    'perPage' => (int) $this->value
                ]);
            } else {
                $this->validatorDataCollector->setRejectedParameters([
                    'perPage' => [
                        'value' => $this->value,
                        'parameterError' => 'This parameter\'s value must be an int.'
                    ]
                ]);
            }
        }
    }
    
    protected function isPageParameterThenValidate(): void
    {
        if ($this->parameterTypeIsNotSet() && $this->key == 'page') {
            $this->parameterType = true;
            
            if ($this->isInt($this->value)) {
                $this->validatorDataCollector->setAcceptedParameters([
                    'page' => (int) $this->value
                ]);
            } else {
                $this->validatorDataCollector->setRejectedParameters([
                    'page' => [
                        'value' => $this->value,
                        'parameterError' => 'This parameter\'s value must be an int.'
                    ]
                ]);
            }
        }
    }

    protected function isColumnDataParameterThenValidate(): void
    {
        if ($this->parameterTypeIsNotSet() && in_array($this->key, ['columndata', 'column_data'])) {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'columnData' => [
                    'value' => $this->value,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter data for this resource/endpoint'
                ]
            ]);
        }
    }

    protected function isFormDataParameterThenValidate(): void
    {
        if ($this->parameterTypeIsNotSet() && in_array($this->key, ['formdata', 'form_data'])) {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'formData' => [
                    'value' => $this->value,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter form data for this resource/endpoint'
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