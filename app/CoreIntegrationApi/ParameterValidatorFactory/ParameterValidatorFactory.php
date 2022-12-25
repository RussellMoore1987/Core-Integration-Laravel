<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory;

use App\CoreIntegrationApi\CIL\CILDataTypeDeterminerFactory;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;

class ParameterValidatorFactory extends CILDataTypeDeterminerFactory
{
    public function getFactoryItem($dataType) : ParameterValidator
    {
        $classPath = 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators';

        $this->factoryReturnArray = [
            'string' => "{$classPath}\StringParameterValidator",
            'json' => "{$classPath}\JsonParameterValidator",
            'date' => "{$classPath}\DateParameterValidator",
            'int' => "{$classPath}\IntParameterValidator",
            'float' => "{$classPath}\FloatParameterValidator",
            'orderby' => "{$classPath}\OrderByParameterValidator",
            'select' => "{$classPath}\SelectParameterValidator",
            'includes' => "{$classPath}\IncludesParameterValidator",
            'methodcalls' => "{$classPath}\MethodCallsParameterValidator",
        ];

        return parent::getFactoryItem($dataType);
    }
}