<?php

namespace App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders;

use Illuminate\Database\Eloquent\Builder;

interface ClauseBuilder 
{
    public function build(Builder $queryBuilder, $data) : Builder;
}