<?php

namespace App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders;

use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\ClauseBuilder;

class FloatWhereClauseBuilder implements ClauseBuilder
{
    public function build(Builder $queryBuilder, $data): Builder
    {
        return $queryBuilder;
    }
}