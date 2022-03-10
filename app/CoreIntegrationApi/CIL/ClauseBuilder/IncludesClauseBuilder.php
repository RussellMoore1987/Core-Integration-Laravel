<?php

namespace App\CoreIntegrationApi\CIL\ClauseBuilder;

use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\ClauseBuilder;

class IncludesClauseBuilder implements ClauseBuilder
{
    public function build(Builder $queryBuilder, $data) : Builder
    {
        return $queryBuilder;
        // TODO: withs in withs, see if I can layer it deeper in a easy way, all the way back around project back to project
    }
}

// TODO: available parameters includes, method calls