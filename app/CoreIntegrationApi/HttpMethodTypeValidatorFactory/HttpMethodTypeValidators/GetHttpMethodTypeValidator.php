<?php

namespace App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators;

use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators\HttpMethodTypeValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetHttpMethodTypeValidator implements HttpMethodTypeValidator
{
    protected $parameterValidatorFactory;
    protected $defaultAcceptableParameters = ['per_page', 'perpage', 'page', 'column_data', 'columndata', 'form_data', 'formdata'];
    protected $getMethodParameterValidatorDefaults = [
        'columns' => 'select', 
        'select' => 'select', 
        'orderby' => 'orderby', 
        'order_by' => 'orderby', 
        'methodcalls' => 'methodcalls',
        'method_calls' => 'methodcalls',
        // TODO: add to documentation relationships
        'relationships' => 'includes',
        'includes' => 'includes',
    ];

    public function __construct(ParameterValidatorFactory $parameterValidatorFactory)
    {
        $this->parameterValidatorFactory = $parameterValidatorFactory;
    }

    public function validateRequest(ValidatorDataCollector $validatorDataCollector, $requestData) : ValidatorDataCollector
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $parameters = $requestData['parameters'];
        $extraData = $requestData['extraData'];

        foreach ($parameters as $key => $value) {
            $key = strtolower($key);
            $data = [$key => $value];
            if (array_key_exists($key, $extraData['acceptableParameters'])) {
                $dataType = $extraData['acceptableParameters'][$key]['type'];
                $this->getMethodParameterValidator($dataType, $data);
            } elseif (array_key_exists($key, $this->getMethodParameterValidatorDefaults)) {
                $dataType = $this->getMethodParameterValidatorDefaults[$key];
                $this->getMethodParameterValidator($dataType, $data);
            } elseif (in_array($key ,$this->defaultAcceptableParameters)) {
                $this->handleDefaultParameters($key, $value);
            } else {
                $this->validatorDataCollector->setRejectedParameter([
                    $key => [
                        'value' => $value,
                        'parameterError' => 'This is an invalid parameter for this resource/endpoint.'
                    ]
                ]);
            }
        }

        return $this->validatorDataCollector;
    }

    protected function getMethodParameterValidator($dataType, $data)
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem($dataType);
        $this->validatorDataCollector = $parameterValidator->validate($this->validatorDataCollector, $data);
    }

    protected function handleDefaultParameters($key, $value)
    {
        if (in_array($key, ['perpage', 'per_page'])) {
            $this->setPerPageParameter($value);
        } elseif ($key == 'page') {
            $this->setPageParameter($value);
        } elseif (in_array($key, ['columndata', 'column_data'])) {
            $this->validatorDataCollector->setAcceptedParameter([
                'columnData' => [
                    'value' => $value,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter data for this resource/endpoint'
                ]
            ]);
        } elseif (in_array($key, ['formdata', 'form_data'])) {
            $this->validatorDataCollector->setAcceptedParameter([
                'formData' => [
                    'value' => $value,
                    'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter form data for this resource/endpoint'
                ]
            ]);
        }
    }

    protected function setPerPageParameter($value)
    {
        if ($this->isInt($value)) {
            $this->validatorDataCollector->setAcceptedParameter([
                'perPage' => (int) $value
            ]);
        } else {
            $this->validatorDataCollector->setRejectedParameter([
                'perPage' => [
                    'value' => $value,
                    'parameterError' => 'This parameter\'s value must be an int.'
                ]
            ]);
        }
    }
    
    protected function setPageParameter($value)
    {
        if ($this->isInt($value)) {
            $this->validatorDataCollector->setAcceptedParameter([
                'page' => (int) $value
            ]);
        } else {
            $this->validatorDataCollector->setRejectedParameter([
                'page' => [
                    'value' => $value,
                    'parameterError' => 'This parameter\'s value must be an int.'
                ]
            ]);
        }
    }

    protected function isInt($value)
    {
        return is_numeric($value) && !str_contains($value, '.');
    }

    // TODO: Test this method.
    protected function throwValidationException($validator) : void
    {
        $response = response()->json([
            'error' => 'Validation failed',
            'errors' => $validator->errors(),
            'message' => 'Validation failed, resend request after adjustments have been made.',
            'status_code' => 422,
        ], 422);
        throw new HttpResponseException($response);
    }
}