<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;

class ActionFinder
{
    public function parseValue(string $parameterValue, ErrorCollector &$errorCollector): array
    {
        if (str_contains($parameterValue, '::')) {
            $parameterValueArray = explode('::', $parameterValue);
    
            $originalComparisonOperator = trim($parameterValueArray[1]);
            $action = strtolower($originalComparisonOperator);
            $value = trim($parameterValueArray[0]);
    
            if (count($parameterValueArray) > 2) {
                $errorCollector->add([
                    'value' => $parameterValue,
                    'valueError' => "Only one comparison operator is permitted per parameter, ex: 123::lt.",
                ]);
                unset($parameterValueArray[0]);
                $action = 'inconclusive';
                $originalComparisonOperator = $parameterValueArray;
            }
        }

        return [
            $value ?? $parameterValue,
            $action ?? null,
            $originalComparisonOperator ?? null
        ];
    }
}
