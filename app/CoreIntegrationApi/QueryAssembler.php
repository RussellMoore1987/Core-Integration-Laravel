<?php

namespace App\CoreIntegrationApi;

interface QueryAssembler 
{
    public function query($validatedQueryData);
}