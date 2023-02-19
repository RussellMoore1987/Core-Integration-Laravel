<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators;

use App\CoreIntegrationApi\ValidatorDataCollector;

// TODO: make a test for this class
class DefaultGetParameterValidator
{
    protected $validatorDataCollector;

    public function validate($key, $value, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;

        if (in_array($key, ['perpage', 'per_page'])) {
            $this->setPerPageParameter($value);
        } elseif ($key == 'page') {
            $this->setPageParameter($value);
        } elseif (in_array($key, ['columndata', 'column_data'])) {
            $this->validatorDataCollector->setAcceptedParameters([
                'columnData' => [
                    'value' => $value,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter data for this resource/endpoint'
                ]
            ]);
        } elseif (in_array($key, ['formdata', 'form_data'])) {
            $this->validatorDataCollector->setAcceptedParameters([
                'formData' => [
                    'value' => $value,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter form data for this resource/endpoint'
                ]
            ]);
        }
    }

    protected function setPerPageParameter($value): void
    {
        if ($this->isInt($value)) {
            $this->validatorDataCollector->setAcceptedParameters([
                'perPage' => (int) $value
            ]);
        } else {
            $this->validatorDataCollector->setRejectedParameters([
                'perPage' => [
                    'value' => $value,
                    'parameterError' => 'This parameter\'s value must be an int.'
                ]
            ]);
        }
    }
    
    protected function setPageParameter($value): void
    {
        if ($this->isInt($value)) {
            $this->validatorDataCollector->setAcceptedParameters([
                'page' => (int) $value
            ]);
        } else {
            $this->validatorDataCollector->setRejectedParameters([
                'page' => [
                    'value' => $value,
                    'parameterError' => 'This parameter\'s value must be an int.'
                ]
            ]);
        }
    }

    protected function isInt($value): bool
    {
        return is_numeric($value) && !str_contains($value, '.');
    }
}