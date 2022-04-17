<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class IntParameterDataProvider implements ParameterDataProvider
{
    protected $apiDataType = 'int';

    public function getData($dataType) : array
    {
        return [
            'apiDataType' => $this->apiDataType,
            'formData' => [],
        ];
    }
}