<?php

namespace App\CoreIntegrationApi\CIL\ClauseBuilder;

use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\ClauseBuilder;

class FloatWhereClauseBuilder implements ClauseBuilder
{
    public function build(Builder $queryBuilder, $data) : Builder
    {
        return $queryBuilder;
    }
}