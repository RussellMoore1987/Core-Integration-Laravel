<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class StringResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'string';

    protected function getParameterData(): void
    {
        $this->formData = [];
        $this->defaultValidationRules = [];
    }
}