<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class DateParameterDataProvider implements ParameterDataProvider
{
    protected $apiDataType = 'date';

    public function getData($dataType) : array
    {
        return [
            'apiDataType' => $this->apiDataType,
            'formData' => [],
        ];
    }
}