<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class JsonParameterDataProvider extends ParameterDataProvider
{
    protected $apiDataType = 'json';

    protected function getFormData()
    {
        $this->formData = [];
        $this->defaultValidationRules = [];
    }
}