<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ComparisonOperatorProvider;
use App\CoreIntegrationApi\QueryIndex;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\IndexParameterValidator;
use App\CoreIntegrationApi\ResourceModelInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\InfoProviderConsts;

// TODO: these
// ! NEEDS testing*********
    // end to end testing, brake up into smaller tests and classes
// limiting HTTP methods per route, overall
// authentication, authentication by route
// SQL restrictions per route

class RestQueryIndex implements QueryIndex
{
    private ResourceModelInfoProvider $resourceProvider;
    private array $validatedMetaData;
    private array $index = [];

    public function __construct(ResourceModelInfoProvider $resourceProvider)
    {
        $this->resourceProvider = $resourceProvider;
    }

    public function get(array $validatedMetaData): array
    {
        $this->validatedMetaData = $validatedMetaData;

        return $this->getApiIndex();
    }

    private function getApiIndex(): array
    {
        $this->compileMainInformation();
        $this->compileOverarchingAuthentication();
        $this->compileApiDocumentation();
        $this->compileRoutes();

        $this->filterIndex();

        return $this->index;
    }

    private function compileMainInformation(): void
    {
        $this->index = $this->getMainInformation();

        $partialOverride = config('coreintegration.partialOverride') ?? true;
        $overrides = config('coreintegration.indexOverrides') ?? [];
        if ($partialOverride === true) {
            foreach ($overrides as $key => $value) {
                $this->index[$key] = $value;
            }
        } else {
            $overrides = config('coreintegration.indexOverrides');
        }
    }

    // TODO: put this in a helper class, and other methods
    private function getMainInformation(): array
    {
        return [
            'about' => [
                'companyName' => 'Placeholder Company',
                'termsOfUse' => 'Placeholder Terms URL',
                'version' => '1.0.0',
                'contact' => 'someone@someone.com',
                'description' => 'v1.0.0 of the api. This API may be used to retrieve data. restrictions and limitations are detailed below in the _______ section.', // TODO: fix this _______
                'siteRoot' => substr($this->validatedMetaData['endpointData']['indexUrl'], 0, -7),
                'apiRoot' => $this->validatedMetaData['endpointData']['indexUrl'],
                'defaultReturnRequestStructure' => config('coreintegration.defaultReturnRequestStructure', 'dataOnly'),
            ]
        ];
    }

    private function compileOverarchingAuthentication(): void
    {
        $authenticationType = config('coreintegration.authenticationType') ?? 'Bearer';
        $getProtected = config('coreintegration.getProtected') ?? true;

        $getMessage = '';
        if ($getProtected === true && $authenticationType === 'Bearer') {
            $getMessage = ", {$authenticationType} token required";
        }

        $this->index['generalDocumentation'] = [
            'mainAuthentication' => "{$authenticationType} token required",
            'httpMethods' => [
                'GET' => "Accepts URL/GET variables{$getMessage}",
                'POST' => "Accepts form data, {$authenticationType} token required",
                'PUT' => "Accepts form data, {$authenticationType} token required",
                'PATCH' => "Accepts form data, {$authenticationType} token required",
                'DELETE' => "Accepts URL/GET variables, {$authenticationType} token required",
            ],
        ];
    }

    private function compileApiDocumentation(): void
    {
        $this->index['generalDocumentation']['defaultParametersForRoutes'] = $this->getDefaultParameters();
        $this->index['generalDocumentation']['parameterDataTypes'] = $this->getParameterDataTypes();
    }

    private function getDefaultParameters(): array
    {
        return [
            'columns' => 'Resources only return the specified columns/parameter per resource item.',
            'orderBy' => [
                'Supply parameter name(s) to order your request.',
                'Options are ::DESC and ::ASC, ::ASC is default ,parameter are separated by a comma.',
                'Example: orderBy=column1::DESC,column2::ASC,column3',
            ],
            'methodCalls' => [
                'Provides the ability to utilize a resources/models custom function, per model returned.',
                'Available method calls are shown on the individual resources.',
            ],
            'includes' => [
                'Provides the ability to include related resources/models.',
                'Available includes are shown on the individual resources.',
                'Example: includes=relatedResource1::columns=column1,column2,relatedResource2::orderBy=column1',
            ],
            'page' => 'Allows you to paginate the results.',
            'perPage' => 'Allows you to set the number of items per page returned.',
            'columnData' => 'Shows only the resource parameter data types.',
            'formData' => 'Shows only the resource form data.',
            'dataOnly' => 'Returns only the data requested.',
            'fullInfo' => 'Returns all information on the resource, including pagination, links, documentation, etc.',
        ];
    }

    // TODO: find a way to make this dynamic
    private function getParameterDataTypes(): array
    {
        return [ // @See ResourceParameterInfoProviderFactory.php
            'notes' => [
                '"parameterDataTypes" references how you can access a given resource parameter.',
                'For "databaseDataTypes" "contains-" triggers functionality that looks for the word after the hyphen in the given data type name of the resource parameter. ex: "contains-int" finds the word "int" in the database data type of "bigint", which determines that resource parameter to be an parameter data type of int.',
            ],
            'options' => ComparisonOperatorProvider::getOptions(),
            'int' => [
                'notes' => [
                    'Only ints, whole numbers are allowed.',
                ],
                'databaseDataTypes' => InfoProviderConsts::INT_TYPE_DETERMINERS, // TODO: is this needed?
                'documentation' => [
                    'arrayOptions' => 'in,notin,between',
                    // arrayExamples: 'in=1,2,3', 'notin=1,2,3', 'between=1,3',
                    'arrayExamples' => [
                        'in' => [
                            'intParameter=1,2,3',
                            'intParameter=1,2,3::in',
                        ],
                        'notin' => [
                            'intParameter=1,2,3::notin',
                        ],
                        'between' => [
                            'intParameter=1,3::between',
                        ],
                    ],
                    // 'singleOptions' => 'gt,gte,lt,lte,equal',
                    'singleOptions' => '>,>=,<,<=,=',
                ]
            ],
            'string' => 'string',
            'json' => 'json',
            'date' => 'date',
            'float' => 'float',
        ];

        // "id": "int",
        // "title": "string",
        // "roles": "string",
        // "client": "string",
        // "description": "string",
        // "content": "json",
        // "video_link": "string",
        // "code_link": "string",
        // "demo_link": "string",
        // "start_date": "date",
        // "end_date": "date",
        // "is_published": "int",
        // "created_at": "date",
        // "updated_at": "date",
        // "budget": "float"
    }

    private function compileRoutes(): void
    {
        $availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        $restrictedMethods = config('coreintegration.restrictedHttpMethods') ?? [];

        foreach ($availableResourceEndpoints as $resource => $resourceClass) {
            $route = $this->validatedMetaData['endpointData']['indexUrl'] . '/' . $resource;
            $this->index['quickRouteReference'][$resource]['url'] = $route;

            $availableMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
            $restrictedRouteMethods = config("coreintegration.routeOptions.{$resource}.restrictedHttpMethods") ?? [];
            $restrictedRouteMethods = array_merge($restrictedMethods, $restrictedRouteMethods);

            $availableMethods = array_diff($availableMethods, $restrictedRouteMethods);
            $this->index['quickRouteReference'][$resource]['availableMethods'] = implode(',', $availableMethods);

            $resourceInfo = $this->resourceProvider->getResourceInfo(new $resourceClass());
            unset($resourceInfo['primaryKeyName']);
            unset($resourceInfo['path']);

            foreach ($resourceInfo['acceptableParameters'] as $parameterName => $parameterArray) {
                unset($resourceInfo['acceptableParameters'][$parameterName]['field']);
                unset($resourceInfo['acceptableParameters'][$parameterName]['type']);
                unset($resourceInfo['acceptableParameters'][$parameterName]['key']);
                unset($resourceInfo['acceptableParameters'][$parameterName]['extra']);
                $resourceInfo['acceptableParameters'][$parameterName]['parameterDataType'] = $parameterArray['apiDataType'];
                unset($resourceInfo['acceptableParameters'][$parameterName]['apiDataType']);
                unset($resourceInfo['acceptableParameters'][$parameterName]['defaultValidationRules']);
            }

            $this->index['routes'][$resource] = $resourceInfo;

            $routeAuth = (bool) config("coreintegration.routeOptions.{$resource}.authenticationToken");
            if ($routeAuth) {
                $this->index['routes'][$resource]['routeSpecificAuthentication'] = true;
                $this->index['quickRouteReference'][$resource]['routeSpecificAuthentication'] = true;
            }
        }
    }

    // ! start here ************************* reason or task at hand
    private function filterIndex(): void
    {
        $indexParameters = array_keys($this->validatedMetaData['acceptedParameters'] ?? []);
        $expectableParameters = [];
        foreach ($indexParameters as $indexParameter) {
            if (in_array($indexParameter, IndexParameterValidator::ACCEPTABLE_PARAMETERS)) {
                $expectableParameters[] = $indexParameter;
            }
        }

        if (!empty($expectableParameters)) {
            $tempIndex = $this->index;
            $this->index = [];
            foreach ($expectableParameters as $indexParameter) {
                $this->index[$indexParameter] = $tempIndex[$indexParameter];
            }
        }
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
