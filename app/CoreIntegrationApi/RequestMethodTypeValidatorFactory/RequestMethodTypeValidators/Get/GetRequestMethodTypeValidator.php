<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\Get;

use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\RequestMethodTypeValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\Get\DefaultGetParameterValidator;
use app\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\Get\IndexParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;

// TODO: make a test for this class
class GetRequestMethodTypeValidator implements RequestMethodTypeValidator
{
    protected ParameterValidatorFactory $parameterValidatorFactory;
    protected DefaultGetParameterValidator $defaultGetParameterValidator;
    protected ValidatorDataCollector $validatorDataCollector;
    protected array $resourceInfo;
    protected bool $parameterType;
    protected string $parameterName;
    protected $parameterValue;
    protected array $defaultResourceParameters = [
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

    public function __construct(ParameterValidatorFactory $parameterValidatorFactory, DefaultGetParameterValidator $defaultGetParameterValidator, IndexParameterValidator $indexParameterValidator)
    {
        $this->parameterValidatorFactory = $parameterValidatorFactory;
        $this->defaultGetParameterValidator = $defaultGetParameterValidator;
        $this->indexParameterValidator = $indexParameterValidator;
    }

    public function validateRequest(ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->resourceInfo = $this->validatorDataCollector->resourceInfo;
        $parameters = $this->validatorDataCollector->parameters;

        foreach ($parameters as $parameterName => $parameterValue) {
            $this->parameterType = false;
            $this->parameterName = strtolower($parameterName);
            $this->parameterValue = $parameterValue;

            $this->isAcceptableParametersThenValidate();
            $this->isIndexRequestThenValidate();
            $this->isDefaultResourceParametersThenValidate();
            $this->isDefaultGetParametersThenValidate();
            $this->isInvalidParametersThenRejected();
        }

        $this->ifNotValidRequestThenThrowException();
    }

    protected function isAcceptableParametersThenValidate(): void
    {
        // TODO-Security: test for vulnerabilities accessing or filtering based off of 'password' or something like that ($this->resourceInfo['acceptableParameters'])
        if ($this->isAcceptable()) {
            $this->parameterType = true;

            $dataType = $this->resourceInfo['acceptableParameters'][$this->parameterName]['apiDataType'];
            $this->getMethodParameterValidator($dataType);
        }
    }

    private function isAcceptable(): bool
    {
        return $this->isParameterTypeNotSet() &&
            $this->resourceInfo &&
            array_key_exists($this->parameterName, $this->resourceInfo['acceptableParameters']);
    }

    protected function isIndexRequestThenValidate(): void
    {
        if ($this->isIndexRequest()) {
            $this->parameterType = true;

            $this->indexParameterValidator->validate($this->parameterName, $this->parameterValue, $this->validatorDataCollector);
        }
    }

    private function isIndexRequest(): bool
    {
        $resource = $this->validatorDataCollector->endpointData['resource'];
        $isIndexRequest = $resource === 'index';
        $isValidParam = in_array($this->parameterName, IndexParameterValidator::DEFAULT_INDEX_PARAMETERS);

        return $this->isParameterTypeNotSet() && $isIndexRequest && $isValidParam;
    }

    protected function isDefaultResourceParametersThenValidate(): void
    {
        if ($this->isParameterTypeNotSet() && array_key_exists($this->parameterName, $this->defaultResourceParameters)) {
            $this->parameterType = true;

            $dataType = $this->defaultResourceParameters[$this->parameterName];
            $this->getMethodParameterValidator($dataType);
        }
    }

    protected function getMethodParameterValidator($dataType): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem($dataType);
        $parameterValidator->validate($this->parameterName, $this->parameterValue, $this->validatorDataCollector);
    }

    protected function isDefaultGetParametersThenValidate()
    {
        if ($this->isParameterTypeNotSet() && in_array($this->parameterName, DefaultGetParameterValidator::DEFAULT_GET_PARAMETERS)) {
            $this->parameterType = true;
            
            $this->defaultGetParameterValidator->validate($this->parameterName, $this->parameterValue, $this->validatorDataCollector);
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

    protected function ifNotValidRequestThenThrowException(): void
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
