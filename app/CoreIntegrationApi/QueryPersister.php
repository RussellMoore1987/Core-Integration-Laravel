<?php

namespace App\CoreIntegrationApi;

interface QueryPersister 
{
    public function persist($validatedQueryData);
}