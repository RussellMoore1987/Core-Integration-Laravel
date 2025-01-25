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
            'companyName' => 'Placeholder Company',
            'termsOfUse' => 'Placeholder Terms URL',
            'version' => '1.0.0',
            'contact' => 'someone@someone.com',
            'description' => 'v1.0.0 of the api. This API may be used to retrieve data from the CMS system and in some cases create data. If the system has an API key it is required on all requests.',
            'siteRoot' => 'MAIN_LINK_PATH',
            'apiRoot' => '$rootLink',
            // general route documentation
            'generalRoutDocumentation' => [
                // Main Authentication
                // TODO: 'authToken' vs bearer token
                'mainAuthentication' => [
                    'authToken' => 'NEEDS NEW INSTRUCTIONS', // TODO: needs new instructions
                    // get bearer token only for get auth
                    // authToken for all other requests is ok
                        // authToken can not be sent in the url
                ],
                'httpMethods' => [
                    'GET' => 'Accepts urlcoded data',
                    'POST' => 'Accepts form data',
                    'PUT' => 'Accepts form data',
                    'PATCH' => 'Accepts form data',
                    'DELETE' => 'Accepts URL/GET variables'
                ],
                'generalInformation' => [
                    'documentationNote' => ''
                ]
            ],
            // routs
            'routs' => []
        ];
    }
}
