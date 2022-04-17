<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class FloatParameterDataProvider implements ParameterDataProvider
{
    protected $apiDataType = 'float';

    public function getData($dataType) : array
    {
        return [
            'apiDataType' => $this->apiDataType,
            'formData' => [],
        ];
    }
}