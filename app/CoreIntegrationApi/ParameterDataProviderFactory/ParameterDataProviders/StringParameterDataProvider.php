<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class StringParameterDataProvider implements ParameterDataProvider
{
    protected $apiDataType = 'string';

    public function getData($dataType) : array
    {
        return [
            'apiDataType' => $this->apiDataType,
            'formData' => [],
        ];
    }
}