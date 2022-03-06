<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory;

use App\CoreIntegrationApi\CIL\CILDataTypeDeterminerFactory;
use Illuminate\Support\Facades\App;

class ParameterValidatorFactory extends CILDataTypeDeterminerFactory
{
    public function getFactoryItem($dataType)
    {
        $this->factoryReturnArray = [
            'string' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator',
            'json' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\JsonParameterValidator',
            'date' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator',
            'int' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator',
            'float' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\FloatParameterValidator',
            'orderby' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\OrderByParameterValidator',
            'select' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\SelectParameterValidator',
            'includes' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IncludesParameterValidator',
            'methodcalls' => 'App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\MethodCallsParameterValidator',
        ];

        return parent::getFactoryItem($dataType);
    }

    protected function returnValue($dataTypeValue)
    {
        return App::make($dataTypeValue);
    }
}