<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;

// TODO: relook at this class

class ActionFinder
{
    public function parseValue(string $requestValue, ErrorCollector &$errorCollector)
    {
        if (str_contains($requestValue, '::')) {
            $requestValueArray = explode('::', $requestValue);
    
            $originalComparisonOperator = trim($requestValueArray[1]);
            $action = strtolower(trim($requestValueArray[1]));
            $value = trim($requestValueArray[0]);
    
            if (count($requestValueArray) > 2) {
                $errorCollector->add([
                    'value' => $requestValue,
                    'valueError' => "Only one comparison operator is permitted per parameter, ex: 123::lt.",
                ]);
                unset($requestValueArray[0]);
                $action = 'inconclusive';
                $originalComparisonOperator = $requestValueArray;
            }
        }

        return [
            $value ?? $requestValue,
            $action ?? null,
            $originalComparisonOperator ?? null
        ];
    }
}
