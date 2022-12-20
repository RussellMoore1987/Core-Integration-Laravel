<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class FloatResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'float';

    protected function getFormData()
    {
        $this->formData = [];
        $this->defaultValidationRules = [];
    }
}