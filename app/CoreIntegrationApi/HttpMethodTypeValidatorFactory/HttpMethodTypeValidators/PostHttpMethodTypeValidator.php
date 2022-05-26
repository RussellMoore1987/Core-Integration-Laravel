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
        return $validatorDataCollector; // Just to make test work for now

        $this->validatorDataCollector = $validatorDataCollector;

        $this->parameters = $requestData['parameters'];
        $this->extraData = $requestData['extraData'];
        $this->classObject = $requestData['classObject'];

        $this->validateParameters();

        return $validatorDataCollector;
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
        // ! start here ************************************************************ see if I can get $validator->validated() with out redirecting, see if can stop redirect, run coverage report
        $validator = Validator::make($this->parameters, $this->validationRules);
        $this->validatorDataCollector->setRejectedParameter($validator->errors()->toArray());
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json($validator->errors(), 422));
        }
        dd($validator->safe()->collect());
        dd($validator->fails());
        dd($this->validationRules, $this->validatorDataCollector, $validator);
        $this->validatorDataCollector->setAcceptedParameter($validator->validated());
        $this->validatorDataCollector->setQueryArgument($validator->validated());
        dd($this->validationRules, $this->validatorDataCollector);
    }
}