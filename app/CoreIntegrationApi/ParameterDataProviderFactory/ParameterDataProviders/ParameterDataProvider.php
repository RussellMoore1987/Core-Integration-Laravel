<?php

namespace App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders;

interface ParameterDataProvider 
{
    public function getData($dataType) : array;
}