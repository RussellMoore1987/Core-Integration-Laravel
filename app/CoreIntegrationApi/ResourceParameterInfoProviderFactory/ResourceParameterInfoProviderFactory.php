<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory;

use App\CoreIntegrationApi\CIL\CILDataTypeDeterminerFactory;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;

class ResourceParameterInfoProviderFactory extends CILDataTypeDeterminerFactory
{
    public function getFactoryItem($dataType) : ResourceParameterInfoProvider
    {
        $classPath = 'App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders';

        $this->factoryReturnArray = [
            'string' => "{$classPath}\StringResourceParameterInfoProvider",
            'json' => "{$classPath}\JsonResourceParameterInfoProvider",
            'date' => "{$classPath}\DateResourceParameterInfoProvider",
            'int' => "{$classPath}\IntResourceParameterInfoProvider",
            'float' => "{$classPath}\FloatResourceParameterInfoProvider",
        ];

        return parent::getFactoryItem($dataType);
    }
}