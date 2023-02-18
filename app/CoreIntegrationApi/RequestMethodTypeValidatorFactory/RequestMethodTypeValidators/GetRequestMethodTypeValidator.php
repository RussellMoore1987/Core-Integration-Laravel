<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators;

use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\RequestMethodTypeValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (sub ParameterValidatorFactory (change if statements to api data types), PostRequestMethodTypeValidator.php)
// [] read over
// [x] add return type : void
// [] add test
// test to do
// [] read over
// [] test groups, rest, context
// [] add return type : void
// [] testing what I need to test

class GetRequestMethodTypeValidator implements RequestMethodTypeValidator
{
    protected $parameterValidatorFactory;
    protected $validatorDataCollector;
    protected $resourceInfo;
    protected $parameterType;
    protected $parameterName;
    protected $parameterValue;
    protected $defaultGetParameters = [
        'per_page',
        'perpage',
        'page',
        'column_data',
        'columndata',
        'form_data',
        'formdata',
    ];
    protected $defaultResourceParameters = [
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

    public function validateRequest(ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->resourceInfo = $this->validatorDataCollector->resourceInfo;
        $parameters = $this->validatorDataCollector->parameters;

        // ! start here *********************************************
        foreach ($parameters as $parameterName => $parameterValue) {
            $this->parameterType = false;
            $this->parameterName = strtolower($parameterName);
            $this->parameterValue = $parameterValue;

            $this->isAcceptableParametersThenValidate();
            $this->isDefaultResourceParametersThenValidate();
            $this->isDefaultGetParametersThenValidate();
            $this->isInvalidParametersThenRejected();
        }

        $this->checkIfValidRequest();
    }

    protected function isAcceptableParametersThenValidate(): void
    {
        // TODO: test for vulnerabilities accessing or filtering based off of password or something like that ($this->resourceInfo['acceptableParameters'])
        if ($this->isParameterTypeNotSet() && array_key_exists($this->parameterName, $this->resourceInfo['acceptableParameters'])) {
            $this->parameterType = true;

            $dataType = $this->resourceInfo['acceptableParameters'][$this->parameterName]['apiDataType'];
            $this->getMethodParameterValidator($dataType, [$this->parameterName => $this->parameterValue]);
        }
    }

    protected function isDefaultResourceParametersThenValidate(): void
    {
        if ($this->isParameterTypeNotSet() && array_key_exists($this->parameterName, $this->defaultResourceParameters)) {
            $this->parameterType = true;

            $dataType = $this->defaultResourceParameters[$this->parameterName];
            $this->getMethodParameterValidator($dataType, [$this->parameterName => $this->parameterValue]);
        }
    }

    protected function getMethodParameterValidator($dataType, $data): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem($dataType);
        $parameterValidator->validate($this->validatorDataCollector, $data);
    }

    protected function isDefaultGetParametersThenValidate()
    {
        if ($this->isParameterTypeNotSet() && in_array($this->parameterName, $this->defaultGetParameters)) {
            $this->parameterType = true;

            $this->handleDefaultParameters($this->parameterName, $this->parameterValue);
        }
    }

    protected function handleDefaultParameters($key, $value): void
    {
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

    protected function isInvalidParametersThenRejected(): void
    {
        if ($this->isParameterTypeNotSet()) {
            $this->validatorDataCollector->setRejectedParameters([
                $this->parameterName => [
                    'value' => $this->parameterValue,
                    'parameterError' => 'This is an invalid parameter for this resource/endpoint.'
                ]
            ]);
        }
    }

    public function isParameterTypeNotSet(): bool
    {
        return !$this->parameterType;
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

    // TODO: Test this method.
    protected function checkIfValidRequest(): void
    {
        if ($this->validatorDataCollector->getRejectedParameters()) {
            $response = response()->json([
                'error' => 'Validation Failed',
                'rejectedParameters' => $this->validatorDataCollector->getRejectedParameters(),
                'acceptedParameters' => $this->validatorDataCollector->getAcceptedParameters(),
                'message' => 'Validation failed, resend request after adjustments have been made.',
                'statusCode' => 422,
            ], 422);
            throw new HttpResponseException($response);
        }
    }
}