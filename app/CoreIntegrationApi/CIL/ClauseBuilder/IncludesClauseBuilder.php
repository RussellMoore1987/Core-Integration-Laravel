<?php

namespace App\CoreIntegrationApi\CIL\ClauseBuilder;

use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\ClauseBuilder;

class IncludesClauseBuilder implements ClauseBuilder
{
    public function build(Builder $queryBuilder, $column, $value) : Builder
    {
        // code...
    }
}