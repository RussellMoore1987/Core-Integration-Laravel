<?php

namespace App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators;

use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators\HttpMethodTypeValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class PostHttpMethodTypeValidator implements HttpMethodTypeValidator
{

    public function validateRequest(ValidatorDataCollector $validatorDataCollector, $requestData) : ValidatorDataCollector
    {
        // return $validatorDataCollector; // Just to make test work for now

        $this->validatorDataCollector = $validatorDataCollector;

        // dd($requestData);

        $this->parameters = $requestData['parameters'];
        $this->extraData = $requestData['extraData'];
        $this->classObject = $requestData['classObject'];

        $this->validateParameters();

        return  $this->validatorDataCollector;
    }

    protected function validateParameters() : void
    {
        $this->setUpValidationRules();
        $this->validate();
    }

    protected function setUpValidationRules() : void
    {
        $validationRules = $this->classObject->validationRules && method_exists($this->classObject, 'getValidationRules') ? $this->classObject->getValidationRules() : [];

        if (!$validationRules) {
            foreach ($this->extraData['acceptableParameters'] as $parameterName => $parameterDetails) {
                $validationRules[$parameterName] = $parameterDetails['defaultValidationRules'];
            }
        }

        $this->validationRules = $validationRules;
    }

    protected function validate() : void
    {
        $validator = Validator::make($this->parameters, $this->validationRules);
        // TODO: do this in the other HttpMethodTypeValidators
        if ($validator->fails()) {
            $this->throwValidationException($validator);
        }
        
        $this->validatorDataCollector->setRejectedParameter(array_diff($this->parameters, $validator->validated()) );
        $this->validatorDataCollector->setAcceptedParameter($validator->validated());
        $this->validatorDataCollector->setQueryArgument($validator->validated());
    }

    // ! start on PostHttpMethodTypeValidatorTest, ***Have I tested everything yet*** validateRequest, validate, setUpValidationRules(), and end to end that can be done **********************************************************************
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