<?php

namespace App\CoreIntegrationApi;

interface ResponseBuilder
{
    public function setValidatedMetaData($validatedMetaData);
    public function setResponseData($queryResult);
    public function make();
}