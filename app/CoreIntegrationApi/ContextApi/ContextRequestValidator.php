<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\RequestValidator;

class ContextRequestValidator extends RequestValidator
{
    // @ uses serves a provider for dependency injection, Located app\Providers\ContextRequestProcessorProvider.php
    
    protected $validatedMetaData = [];

    public function validate(): array
    {
        $this->requestDataPrepper->prep();

        // TODO: set request name
        foreach ($this->requestDataPrepper->getPreppedData() as $prepRequestData) {
            $this->validateRequest($prepRequestData);
        }

        return $this->validatedMetaData;
    }

    protected function setUpPreppedDataForValidation($prepRequestData): void
    {
        parent::setUpPreppedDataForValidation($prepRequestData);
    }

    protected function setValidatedMetaData(): void
    {
        $validatedRequestMetaData['rejectedParameters'] = $this->validatorDataCollector->getRejectedParameters();
        $validatedRequestMetaData['acceptedParameters'] = $this->validatorDataCollector->getAcceptedParameters();
        $this->validatedMetaData[] = $validatedRequestMetaData;

        $this->validatorDataCollector->reset();
    }
}
