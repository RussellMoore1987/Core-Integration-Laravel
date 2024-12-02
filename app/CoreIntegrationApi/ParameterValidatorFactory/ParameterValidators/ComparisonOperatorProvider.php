<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;

class ComparisonOperatorProvider
{
    protected $filterOptions;
    protected $comparisonOperatorOptions;
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

    public function select(string $action, ErrorCollector &$errorCollector, array $filterOptions = null): string
    {
        $this->filterOptions = $filterOptions;

        $this->comparisonOperatorOptions = $this->filterOptionsIsNotSet() ? $this->comparisonOperatorMatrix : $this->findComparisonOperatorSubSet();

        return $this->findComparisonOperator(strtolower($action), $errorCollector);
    }

    protected function filterOptionsIsNotSet(): bool
    {
        return !$this->filterOptions;
    }

    protected function findComparisonOperatorSubSet(): array
    {
        foreach ($this->filterOptions as $operator) {
            $comparisonOperatorOptions[$operator] = $this->comparisonOperatorMatrix[$operator]; // TODO: test for error
        }
        return $comparisonOperatorOptions ?? [];
    }

    protected function findComparisonOperator(string $action, ErrorCollector $errorCollector): string
    {
        foreach ($this->comparisonOperatorOptions as $comparisonOperator => $actionOptions) {
            if (in_array($action, $actionOptions)) {
                return $comparisonOperator;
            }
        }

        $errorCollector->add([
            'value' => $action,
            'valueError' => "The comparison operator is invalid. The comparison operator of \"{$action}\" does not exist for this parameter.",
        ]);

        return '';
    }
}

