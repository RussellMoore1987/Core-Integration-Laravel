<?php

namespace App\CoreIntegrationApi\RestApi\RestQueryIndex;

use App\CoreIntegrationApi\QueryIndex;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\Get\IndexParameterValidator;

// TODO: these
// ! NEEDS testing*********
    // ! start here ************************* reason or task at hand
    // end to end testing (done), brake up into smaller tests and classes
// limiting HTTP methods per route, overall
// authentication, authentication by route
// SQL restrictions per route

class RestQueryIndex implements QueryIndex
{
    private RestIndexHelper $helper;
    private array $validatedMetaData;

    public function __construct(RestIndexHelper $helper)
    {
        $this->helper = $helper;
    }

    public function get(array $validatedMetaData): array
    {
        $this->validatedMetaData = $validatedMetaData;
        $this->helper->setMetaData($this->validatedMetaData);

        return $this->getApiIndex();
    }

    private function getApiIndex(): array
    {
        $exParams = $this->getExpectableParameters();

        $expectableParameters = $exParams ? $exParams : IndexParameterValidator::ACCEPTABLE_PARAMETERS;

        foreach ($expectableParameters as $expectableParameter) {
            $this->helper->$expectableParameter();
        }

        return $this->helper->getIndex();
    }

    private function getExpectableParameters(): array
    {
        $indexParameters = array_keys($this->validatedMetaData['acceptedParameters'] ?? []);
        $expectableParameters = [];
        foreach ($indexParameters as $indexParameter) {
            if (in_array($indexParameter, IndexParameterValidator::ACCEPTABLE_PARAMETERS)) {
                $expectableParameters[] = $indexParameter;
            }
        }

        return $expectableParameters;
    }
}

// TODO: Need to find a better way to test config values ***************************************************************

// TODO: add this
// "info": {
//         "message": "Documentation on how to utilize parameter data types can be found in the index response, in the ApiDocumentation section.",
//         "index_url": "http://localhost:8000/api/v1/"
//     },

// // general route documentation
// 'generalRoutDocumentation' => [
//     // Main Authentication
//     // TODO: 'authToken' vs bearer token
//     'mainAuthentication' => [
//         'authToken' => 'NEEDS NEW INSTRUCTIONS', // TODO: needs new instructions
//         // get bearer token only for get auth
//         // authToken for all other requests is ok
//             // authToken can not be sent in the url
//     ],
//     'httpMethods' => [
//         'GET' => 'Accepts urlcoded data',
//         'POST' => 'Accepts form data',
//         'PUT' => 'Accepts form data',
//         'PATCH' => 'Accepts form data',
//         'DELETE' => 'Accepts URL/GET variables'
//     ],
// ],
