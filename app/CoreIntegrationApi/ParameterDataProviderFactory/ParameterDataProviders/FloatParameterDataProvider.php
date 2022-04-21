<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class FloatParameterDataProvider extends ParameterDataProvider
{
    protected $apiDataType = 'float';

    protected function getFormData()
    {
        $this->formData = [];
    }
}