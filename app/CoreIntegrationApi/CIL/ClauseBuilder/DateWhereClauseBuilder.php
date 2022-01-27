<?php

namespace App\CoreIntegrationApi\CIL\ClauseBuilder;

use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\ClauseBuilder;

class DateWhereClauseBuilder implements ClauseBuilder
{
    private $queryBuilder;
    private $columnName;
    private $date;
    private $dateAction;
    private $comparisonOperator;

    public function build(Builder $queryBuilder, $data) : Builder
    {
        $this->setMainVariables($queryBuilder, $data);
        $this->processDateData();
        $this->buildDateClause();

        return $this->queryBuilder;
    } 

    private function setMainVariables($queryBuilder, $data)
    {
        $this->queryBuilder = $queryBuilder;

        extract($data, EXTR_OVERWRITE); // $columnName & $date are now available
        $this->columnName = $columnName;
        $this->date = $date;
    }

    private function processDateData()
    {
        $this->processDateString();
        $this->setComparisonOperator();
    }

    private function processDateString()
    {
        if (str_contains($this->date, '::')) {
            $date_array = explode('::', $this->date);
    
            $this->dateAction = strtolower($date_array[1]);
    
            if (str_contains($date_array[0], ',')) {
                $between_dates = explode(',', $date_array[0]);
                $this->date[] = date('Y-m-d', strtotime($between_dates[0])); // Beginning of day - default 1970-01-01
                $this->date[] = date('Y-m-d H:i:s', strtotime("tomorrow", strtotime($between_dates[1])) - 1); // End of day - default 1970-01-01 23:59:59
            } else {
                $this->date = date('Y-m-d', strtotime($date_array[0]));
            }

        } else {
            $this->date = date('Y-m-d', strtotime($this->date));
        }
    }

    private function setComparisonOperator()
    {
        if (in_array($this->dateAction, ['greaterthan', 'gt'])) {
            $this->comparisonOperator = '>';
        } else if (in_array($this->dateAction, ['greaterthanorequal', 'gte'])) {
            $this->comparisonOperator = '>=';
        } else if (in_array($this->dateAction, ['lessthan', 'lt'])) {
            $this->comparisonOperator = '<';
        } else if (in_array($this->dateAction, ['lessthanorequal', 'lte'])) {
            $this->comparisonOperator = '<=';
        } else if (in_array($this->dateAction, ['between', 'bt'])) {
            $this->comparisonOperator = 'bt';
        } else {
            $this->comparisonOperator = '=';
        }
    }

    private function buildDateClause()
    {
        if ($this->comparisonOperator == 'bt') {
            $this->queryBuilder->whereBetween($this->columnName, $this->date);
        } else {
            $this->queryBuilder->whereDate($this->columnName, $this->comparisonOperator, $this->date);
        }
    }
}