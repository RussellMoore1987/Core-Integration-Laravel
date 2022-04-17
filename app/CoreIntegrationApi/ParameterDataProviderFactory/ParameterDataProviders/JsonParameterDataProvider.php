<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class JsonParameterDataProvider implements ParameterDataProvider
{
    protected $apiDataType = 'json';

    public function getData($dataType) : array
    {
        return [
            'apiDataType' => $this->apiDataType,
            'formData' => [],
        ];
    }
}