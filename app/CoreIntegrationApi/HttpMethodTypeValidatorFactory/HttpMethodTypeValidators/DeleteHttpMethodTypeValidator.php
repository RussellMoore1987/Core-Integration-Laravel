<?php

namespace App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators;

use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators\HttpMethodTypeValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteHttpMethodTypeValidator implements HttpMethodTypeValidator
{
    public function validateRequest(ValidatorDataCollector $validatorDataCollector, $requestData) : ValidatorDataCollector
    {
        return $validatorDataCollector;
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