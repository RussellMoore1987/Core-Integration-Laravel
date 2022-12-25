<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\QueryIndex;

class RestQueryIndex implements QueryIndex
{
    public function get()
    {
        return 'index';
    }
}