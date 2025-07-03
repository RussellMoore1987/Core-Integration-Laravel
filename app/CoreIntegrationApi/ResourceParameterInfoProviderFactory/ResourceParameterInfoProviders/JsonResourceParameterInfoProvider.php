<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

// ? https://dev.mysql.com/doc/refman/8.0/en/json.html Data type details
class JsonResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'json';

    protected function setParameterData(): void
    {
        $this->isJsonThenSetParameterInfo();
    }

    protected function isJsonThenSetParameterInfo(): void
    {
        if ($this->isJsonType('json')) {
            
            $this->defaultValidationRules = [
                'json',
            ];

            $this->formData = [
                'type' => 'textarea',
                'placeholder' => 'Enter valid JSON...',
            ];
        }
    }
    
    protected function isJsonType($jsonType): bool
    {
        return str_contains($this->parameterDataType, $jsonType) ? true : false;
    }
}