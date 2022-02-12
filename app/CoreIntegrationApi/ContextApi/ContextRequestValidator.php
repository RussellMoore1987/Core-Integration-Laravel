<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\RequestValidator;

class ContextRequestValidator extends RequestValidator
{
    protected $validatedMetaData = [];

    // uses serves provider Located app\Providers\ContextRequestProcessorProvider.php

    public function validate()
    {
        $this->requestDataPrepper->prep();

        // TODO: set request name
        foreach ($this->requestDataPrepper->getPreppedData() as $prepRequestData) {
            $this->validateRequest($prepRequestData);
        }

        return $this->validatedMetaData;
    }

    protected function setUpPreppedRequest($prepRequestData)
    {
        parent::setUpPreppedRequest($prepRequestData);
        
        $this->validatorDataCollector->reset();
    }

    protected function setValidatedMetaData()
    {
        $validatedRequestMetaData['rejectedParameters'] = $this->validatorDataCollector->getRejectedParameters();
        $validatedRequestMetaData['acceptedParameters'] = $this->validatorDataCollector->getAcceptedParameters();
        $this->validatedMetaData[] = $validatedRequestMetaData;
    }
}
