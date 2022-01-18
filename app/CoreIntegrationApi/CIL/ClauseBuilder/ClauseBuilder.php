<?php

namespace App\CoreIntegrationApi\CIL\ClauseBuilder;

use Illuminate\Database\Eloquent\Builder;

interface ClauseBuilder 
{
    public function build(Builder $queryBuilder, $data) : Builder;
}