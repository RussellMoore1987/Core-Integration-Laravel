<?php

namespace App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\Get;

use App\CoreIntegrationApi\ValidatorDataCollector;

class IndexParameterValidator
{
    const DEFAULT_INDEX_PARAMETERS = [
        'about',
        'generaldocumentation', 'general_documentation',
        'quickroutereference', 'quick_route_reference',
        'routes'
    ];
    const ACCEPTABLE_PARAMETERS = [
        'about',
        'generalDocumentation',
        'quickRouteReference',
        'routes'
    ];

    protected ?bool $parameterType;
    protected string $parameterName;
    protected string $parameterValue;
    protected ValidatorDataCollector $validatorDataCollector;

    public function validate(string $parameterName, string $parameterValue, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->parameterType = null; // this is set for resetting purposes
        $this->parameterName = $parameterName;
        $this->parameterValue = $parameterValue;
        $this->validatorDataCollector = $validatorDataCollector;

        $this->isAboutParameterThenValidate();
        $this->isGeneralDocumentationParameterThenValidate();
        $this->isQuickRouteReferenceParameterThenValidate();
        $this->isRoutesParameterThenValidate();
    }

    protected function isAboutParameterThenValidate(): void
    {
        if ($this->parameterIsNotSet() && $this->parameterName == 'about') {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'about' => [
                    'value' => $this->parameterValue,
                    'message' => 'This parameter\'s value dose not matter. If set it will return the about information for the API index.'
                ]
            ]);
        }
    }
    
    protected function isGeneralDocumentationParameterThenValidate(): void
    {
        if ($this->parameterIsNotSet() && in_array($this->parameterName, ['generaldocumentation', 'general_documentation'])) {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'generalDocumentation' => [
                    'value' => $this->parameterValue,
                    'message' => 'This parameter\'s value dose not matter. If set it will return the general documentation information for the API index.'
                ]
            ]);
        }
    }

    protected function isQuickRouteReferenceParameterThenValidate(): void
    {
        if ($this->parameterIsNotSet() && in_array($this->parameterName, ['quickroutereference', 'quick_route_reference'])) {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'quickRouteReference' => [
                    'value' => $this->parameterValue,
                    'message' => 'This parameter\'s value dose not matter. If set it will return the quick route reference information for the API index.'
                ]
            ]);
        }
    }

    protected function isRoutesParameterThenValidate(): void
    {
        if ($this->parameterIsNotSet() && $this->parameterName == 'routes') {
            $this->parameterType = true;
            
            $this->validatorDataCollector->setAcceptedParameters([
                'routes' => [
                    'value' => $this->parameterValue,
                    'message' => 'This parameter\'s value dose not matter. If set it will return the routes information for the API index.'
                ]
            ]);
        }
    }
    
    protected function parameterIsNotSet(): bool
    {
        return !$this->parameterType;
    }
}
