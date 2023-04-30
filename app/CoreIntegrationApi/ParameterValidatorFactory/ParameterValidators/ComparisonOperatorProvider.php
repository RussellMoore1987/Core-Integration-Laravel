<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;

// TODO: look over and review

class ComparisonOperatorProvider
{
    protected $acceptableComparisonOperatorOptions;
    protected $comparisonOperatorMatrix = [
        '=' => ['equal', 'e', '='],
        '>' => ['greaterthan', 'gt', '>'],
        '>=' => ['greaterthanorequal', 'gte', '>='],
        '<' => ['lessthan', 'lt', '<'],
        '<=' => ['lessthanorequal', 'lte', '<='],
        'bt' => ['between', 'bt'],
        'in' => ['in'],
        'notin' => ['notin', 'ni'],
    ];

    public function select(string $action, ErrorCollector &$errorCollector, array $acceptableComparisonOperators = null): string
    {
        $this->acceptableComparisonOperatorOptions = !$acceptableComparisonOperators ? $this->comparisonOperatorMatrix : $this->findAcceptableComparisonOperatorSubSet($acceptableComparisonOperators);

        return $this->findComparisonOperator(strtolower($action), $errorCollector);
    }

    protected function findAcceptableComparisonOperatorSubSet(array $acceptableComparisonOperators): array
    {
        foreach ($acceptableComparisonOperators as $operator) {
            $acceptableComparisonOperatorOptions[$operator] = $this->comparisonOperatorMatrix[$operator];
        }
        return $acceptableComparisonOperatorOptions ?? [];
    }

    protected function findComparisonOperator(string $action, $errorCollector): string
    {
        foreach ($this->acceptableComparisonOperatorOptions as $comparisonOperator => $actionOptions) {
            if (in_array($action, $actionOptions)) {
                return $comparisonOperator;
            }
        }

        // TODO: pass by reference
        $errorCollector->add([
            'value' => $action,
            'valueError' => "The comparison operator is invalid. The comparison operator of \"{$action}\" does not exist for this parameter.",
        ]);

        return '';
    }
}

