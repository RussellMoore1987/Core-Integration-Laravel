<?php

namespace App\CoreIntegrationApi\RestApi\RestQueryIndex;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ComparisonOperatorProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\InfoProviderConsts;

class DocumentationHelper
{
    private array $generalDocumentation = [];

    public function getApiDocumentation(): array
    {
        $this->compileOverarchingAuthentication();
        $this->compileApiDocumentation();

        return $this->generalDocumentation;
    }

    private function compileOverarchingAuthentication(): void
    {
        $authenticationType = config('coreintegration.authenticationType') ?? 'Bearer';
        $getProtected = config('coreintegration.getProtected') ?? true;

        $getMessage = '';
        if ($getProtected === true && $authenticationType === 'Bearer') {
            $getMessage = ", {$authenticationType} token required";
        }

        $this->generalDocumentation = [
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
        $this->generalDocumentation['defaultParametersForRoutes'] = $this->getDefaultParameters();
        $this->generalDocumentation['parameterDataTypes'] = $this->getParameterDataTypes();
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

}
