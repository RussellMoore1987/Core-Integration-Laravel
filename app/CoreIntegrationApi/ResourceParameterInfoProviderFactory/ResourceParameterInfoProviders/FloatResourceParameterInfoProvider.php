<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class FloatResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'float';

    protected function setParameterData(): void
    {
        $this->formData = ['min' => -128];
        $this->defaultValidationRules = ['min:-128'];
    }
}