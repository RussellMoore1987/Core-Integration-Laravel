<?php

namespace App\CoreIntegrationApi\CIL\ClauseBuilder;

use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\ClauseBuilder;

class DateWhereClauseBuilder implements ClauseBuilder
{
    public function build(Builder $queryBuilder, $data) : Builder
    {
        extract($data, EXTR_OVERWRITE); // $columnName, $date and $comparisonOperator are now available
        
        if ($comparisonOperator == 'bt') {
            $queryBuilder->whereBetween($columnName, $date);
        } else {
            $queryBuilder->whereDate($columnName, $comparisonOperator, $date);
        }

        return $queryBuilder;
    } 
}