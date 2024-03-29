<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators;

use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\RequestMethodTypeValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class PostRequestMethodTypeValidator implements RequestMethodTypeValidator
{
    protected $validatorDataCollector;
    protected $parameters;
    protected $resourceInfo;
    protected $resourceObject;
    protected $validationRules;

    public function validateRequest(ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;

        $this->parameters = $this->validatorDataCollector->parameters;
        $this->resourceInfo = $this->validatorDataCollector->resourceInfo;
        $this->resourceObject = $this->validatorDataCollector->resourceObject;

        $this->validateParameters();
    }

    protected function validateParameters(): void
    {
        $this->setUpValidationRules();
        $this->validate();
    }

    protected function setUpValidationRules(): void
    {
        // TODO: do I want $this->resourceObject->validationRules to be public or protected
        $validationRules = $this->resourceObject->validationRules && method_exists($this->resourceObject, 'getValidationRules') ? $this->resourceObject->getValidationRules(): [];

        if (!$validationRules) {
            foreach ($this->resourceInfo['acceptableParameters'] as $parameterName => $parameterDetails) {
                $validationRules[$parameterName] = $parameterDetails['defaultValidationRules'];
            }
        }

        $this->validationRules = $validationRules;
    }

    protected function validate(): void
    {
        $validator = Validator::make($this->parameters, $this->validationRules);
        // TODO: do this in the other RequestMethodTypeValidators ? trait, class
        if ($validator->fails()) {
            $this->throwValidationException($validator);
        }
        
        $this->validatorDataCollector->setRejectedParameters(array_diff($this->parameters, $validator->validated()));
        $this->validatorDataCollector->setAcceptedParameters($validator->validated());
        $this->validatorDataCollector->setQueryArgument($validator->validated());
    }

    protected function throwValidationException($validator): void
    {
        $response = response()->json([
            'error' => 'Validation failed',
            'errors' => $validator->errors(),
            'message' => 'Validation failed, resend request after adjustments have been made.',
            'statusCode' => 422,
        ], 422);
        throw new HttpResponseException($response);
    }
}