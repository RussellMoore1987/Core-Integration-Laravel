<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory;

use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\JsonParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\FloatParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\OrderByParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\SelectParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IncludesParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\MethodCallsParameterValidator;

class ParameterValidatorFactory extends DataTypeDeterminerFactory
{
    protected $factoryItemArray = [
        'string' => StringParameterValidator::class,
        'json' => JsonParameterValidator::class,
        'date' => DateParameterValidator::class,
        'int' => IntParameterValidator::class,
        'float' => FloatParameterValidator::class,
        'orderby' => OrderByParameterValidator::class,
        'select' => SelectParameterValidator::class,
        'includes' => IncludesParameterValidator::class,
        'methodcalls' => MethodCallsParameterValidator::class,
    ];

    public function getFactoryItem($dataType): ParameterValidator
    {
        return parent::getFactoryItem($dataType);
    }
}