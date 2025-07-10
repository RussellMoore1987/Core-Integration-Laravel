<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\QueryIndex;

class ContextQueryIndex implements QueryIndex
{
    public function get(array $validatedMetaData): array
    {
        // get context index
        return $validatedMetaData;
    }
}