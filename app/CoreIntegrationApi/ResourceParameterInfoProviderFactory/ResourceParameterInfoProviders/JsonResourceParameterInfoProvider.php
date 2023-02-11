<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class JsonResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'json';

    protected function setParameterData(): void
    {
        $this->formData = ['min' => -128];
        $this->defaultValidationRules = ['min:-128'];
    }
}