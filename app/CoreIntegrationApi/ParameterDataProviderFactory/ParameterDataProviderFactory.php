<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory;

use App\CoreIntegrationApi\CIL\CILDataTypeDeterminerFactory;
use Illuminate\Support\Facades\App;

class ParameterDataProviderFactory extends CILDataTypeDeterminerFactory
{
    public function getFactoryItem($dataType)
    {
        $this->factoryReturnArray = [
            'string' => 'App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\StringParameterDataProvider',
            'json' => 'App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\JsonParameterDataProvider',
            'date' => 'App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\DateParameterDataProvider',
            'int' => 'App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\IntParameterDataProvider',
            'float' => 'App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\FloatParameterDataProvider',
        ];

        return parent::getFactoryItem($dataType);
    }

    protected function returnValue($dataTypeValue)
    {
        return App::make($dataTypeValue);
    }
}