<?php

namespace App\CoreIntegrationApi;

interface ResponseBuilder
{
    public function setValidationMetaData($metaData);
    public function setResponseData($queryResult);
    public function make();
}