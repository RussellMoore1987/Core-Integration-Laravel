<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class StringParameterDataProvider extends ParameterDataProvider
{
    protected $apiDataType = 'string';

    protected function getFormData()
    {
        $this->formData = [];
        $this->defaultValidationRules = [];
    }
}