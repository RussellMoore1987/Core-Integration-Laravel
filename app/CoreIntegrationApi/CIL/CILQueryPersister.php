<?php

namespace App\CoreIntegrationApi\CIL;

use App\CoreIntegrationApi\QueryPersister;

class CILQueryPersister implements QueryPersister
{
    public function persist($validatedQueryData)
    {
        // if ($validatedQueryData->action == 'PATCH') {
        //     // find record
        //     // merge filds
        //     // save & validate new record
        // } else {
        //     // save & validate record
        //         // id -> update PUT
        //         // no id -> new POST
        // }
    }
}