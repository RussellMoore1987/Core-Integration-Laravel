<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class StringResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'string';

    protected function setParameterData(): void
    {
        $this->formData = ['min' => -128];
        $this->defaultValidationRules = ['min:-128'];
    }
}