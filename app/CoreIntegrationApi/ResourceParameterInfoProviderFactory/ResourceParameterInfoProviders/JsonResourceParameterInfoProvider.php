<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class JsonResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected $apiDataType = 'json';

    protected function getParameterData()
    {
        $this->formData = [];
        $this->defaultValidationRules = [];
    }
}