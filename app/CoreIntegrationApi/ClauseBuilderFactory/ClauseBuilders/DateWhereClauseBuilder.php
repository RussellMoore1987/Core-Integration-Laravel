<?php

namespace App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders;

use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\ClauseBuilder;

class DateWhereClauseBuilder implements ClauseBuilder
{
    public function build(Builder $queryBuilder, $data) : Builder
    {
        extract($data, EXTR_OVERWRITE); // $columnName, $date and $comparisonOperator are now available
        
        if ($comparisonOperator == 'bt') {
            $queryBuilder->whereBetween($columnName, $date);
        } elseif (str_contains($date, ':') && !str_contains($date, '00:00:00')) {
            $queryBuilder->where($columnName, $comparisonOperator, $date);
        } else {
            $queryBuilder->whereDate($columnName, $comparisonOperator, $date);
        }

        return $queryBuilder;
    } 
}