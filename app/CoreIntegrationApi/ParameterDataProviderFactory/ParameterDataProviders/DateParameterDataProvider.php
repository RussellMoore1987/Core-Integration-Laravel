<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class DateParameterDataProvider extends ParameterDataProvider
{
    protected $apiDataType = 'date';

    protected function getFormData()
    {
        $this->formData = [];
        $this->defaultValidationRules = [];
    }
}