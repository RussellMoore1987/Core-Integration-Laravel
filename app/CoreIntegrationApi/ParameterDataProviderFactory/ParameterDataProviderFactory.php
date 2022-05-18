<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory;

use App\CoreIntegrationApi\CIL\CILDataTypeDeterminerFactory;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\ParameterDataProvider;

class ParameterDataProviderFactory extends CILDataTypeDeterminerFactory
{
    public function getFactoryItem($dataType) : ParameterDataProvider
    {
        $classPath = 'App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders';

        $this->factoryReturnArray = [
            'string' => "{$classPath}\StringParameterDataProvider",
            'json' => "{$classPath}\JsonParameterDataProvider",
            'date' => "{$classPath}\DateParameterDataProvider",
            'int' => "{$classPath}\IntParameterDataProvider",
            'float' => "{$classPath}\FloatParameterDataProvider",
        ];

        return parent::getFactoryItem($dataType);
    }
}