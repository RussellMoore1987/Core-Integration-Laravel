<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\RequestValidator;

class ContextRequestValidator extends RequestValidator
{
    // @ uses serves a provider for dependency injection, Located app\Providers\ContextRequestProcessorProvider.php
    
    protected $validatedMetaData = [];

    public function validate()
    {
        $this->requestDataPrepper->prep();

        // TODO: set request name
        foreach ($this->requestDataPrepper->getPreppedData() as $prepRequestData) {
            $this->validateRequest($prepRequestData);
        }

        return $this->validatedMetaData;
    }

    protected function setUpPreppedDataForValidation($prepRequestData)
    {
        parent::setUpPreppedDataForValidation($prepRequestData);
        
        $this->validatorDataCollector->reset();
    }

    protected function setValidatedMetaData()
    {
        $validatedRequestMetaData['rejectedParameters'] = $this->validatorDataCollector->getRejectedParameters();
        $validatedRequestMetaData['acceptedParameters'] = $this->validatorDataCollector->getAcceptedParameters();
        $this->validatedMetaData[] = $validatedRequestMetaData;
    }
}
