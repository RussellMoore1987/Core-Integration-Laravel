<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory;

use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\StringResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\JsonResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\DateResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\IntResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\FloatResourceParameterInfoProvider;

class ResourceParameterInfoProviderFactory extends DataTypeDeterminerFactory
{
    protected $factoryItemArray = [
        'string' => StringResourceParameterInfoProvider::class,
        'json' => JsonResourceParameterInfoProvider::class,
        'date' => DateResourceParameterInfoProvider::class,
        'int' => IntResourceParameterInfoProvider::class,
        'float' => FloatResourceParameterInfoProvider::class,
    ];

    public function getFactoryItem($dataType) : ResourceParameterInfoProvider
    {
        return parent::getFactoryItem($dataType);
    }
}
