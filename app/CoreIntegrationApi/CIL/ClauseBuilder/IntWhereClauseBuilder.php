<?php

namespace App\CoreIntegrationApi\CIL\ClauseBuilder;

use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\ClauseBuilder;

class IntWhereClauseBuilder implements ClauseBuilder
{
    public function build(Builder $queryBuilder, $data) : Builder
    {
        extract($data, EXTR_OVERWRITE); // $columnName, $int and $comparisonOperator are now available
        
        if ($comparisonOperator == 'bt') {
            $queryBuilder->whereBetween($columnName, $int);
        } elseif ($comparisonOperator == 'in') {
            $queryBuilder->whereIn($columnName, $int);
        } elseif ($comparisonOperator == 'notIn') {
            $queryBuilder->whereNotIn($columnName, $int);
        } else {
            $queryBuilder->where($columnName, $comparisonOperator, $int);
        }

        return $queryBuilder;
    }
}