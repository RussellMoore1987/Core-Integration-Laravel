<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\QueryIndex;

class RestQueryIndex implements QueryIndex
{
    public function get(): array
    {
        $apiRoot = $this->getApiRoot();

        return $apiRoot;
    }

    private function getApiRoot(): array
    {
        return [
            // General Info
            "companyName" => "Placeholder Company",
            "termsOfUse" => "Placeholder Terms URL",
            "version" => "1.0.0",
            "contact" => "someone@someone.com",
            "description" => "V1.0.0 of the api. This API may be used to retrieve data from the CMS system and in some cases create data. If the system has an API key it is required on all requests.",
            "siteRoot" => 'MAIN_LINK_PATH',
            "mainApiPath" => '$rootLink',
            // general route documentation
            "generalRoutDocumentation" => [
                // Main Authentication
                "mainAuthentication" => [
                    "authToken" => "If the system has an API key, it is required on all requests. On POST, PUT, PATCH, and DELETE requests an API key (authToken) is require, POST, PUT, and PATCH requests, the API key must be sent as a post parameter/argument. DELETE requests information can be in the URL or the x-www-form-urlcoded body",
                    "default" => "none",
                    "example" => '$rootLink' . "categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
                ],
                "httpMethods" => [
                    "GET" => "Accepts URL/GET variables",
                    "POST" => "Accepts form data/x-www-form-urlcoded data",
                    "PUT" => "Accepts x-www-form-urlcoded data",
                    "PATCH" => "Accepts x-www-form-urlcoded data",
                    "DELETE" => "Accepts URL/GET variables/x-www-form-urlcoded data"
                ],
                "generalInformation" => [
                    "documentationNote" => "This is a general message for all endpoints. On creation of a new record (POST/PATCH) calls the validation of all properties. Required properties must be provided, all others will be passed through according to their validation. On update (PUT), only the fields that are pass-through will be updated.",
                    "validationDocumentation" => 'val_validation_documentation()'
                ]
            ],
            // routs
            "routs" => []
        ];
    }
}
