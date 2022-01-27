<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\RequestProcessor;

class RestRequestProcessor extends RequestProcessor {
    // uses serves provider Located app\Providers\RestRequestProcessorProvider.php
    // As of 1/1/22 loads __construct(RestRequestValidator $requestValidator, RestQueryResolver $queryResolver, RestResponseBuilder $responseBuilder)
}