<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\RequestValidator;

class RestRequestValidator extends RequestValidator
{
    // uses serves provider Located app\Providers\RestRequestProcessorProvider.php
    // As of 1/1/22 loads function __construct(RestRequestDataPrepper $requestDataPrepper)
}