<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestQueryIndex\DocumentationHelper;
use Tests\TestCase;

class DocumentationHelperTest extends TestCase
{
    public function test_get_api_documentation()
    {
        $this->docHelper = new DocumentationHelper();

        $documentation = $this->docHelper->getApiDocumentation();

        $this->assertCount(4, $documentation);
        $this->assertArrayHasKey('mainAuthentication', $documentation);
        $this->assertArrayHasKey('httpMethods', $documentation);
        $this->assertArrayHasKey('defaultParametersForRoutes', $documentation);
        $this->assertArrayHasKey('parameterDataTypes', $documentation);

        $this->assertAuthenticationPart($documentation);
        $this->assertDefaultParametersForRoutesPart($documentation);
        $this->assertParameterDataTypesPart($documentation);
    }

    private function assertAuthenticationPart(array $documentation): void
    {
        $this->assertEquals('Bearer token required', $documentation['mainAuthentication']);
        $this->assertEquals([
            'GET' => 'Accepts URL/GET variables',
            'POST' => 'Accepts form data, Bearer token required',
            'PUT' => 'Accepts form data, Bearer token required',
            'PATCH' => 'Accepts form data, Bearer token required',
            'DELETE' => 'Accepts URL/GET variables, Bearer token required',
        ], $documentation['httpMethods']);
    }

    private function assertDefaultParametersForRoutesPart(array $documentation): void
    {
        $this->assertEquals([
            'columns' => 'Resources only return the specified columns/parameter per resource item.',
            'orderBy' => [
                'Supply parameter name(s) to order your request.',
                'Options are ::DESC and ::ASC, ::ASC is default ,parameter are separated by a comma.',
                'Example: orderBy=column1::DESC,column2::ASC,column3'
            ],
            'methodCalls' => [
                'Provides the ability to utilize a resources/models custom function, per model returned.',
                'Available method calls are shown on the individual resources.'
            ],
            'includes' => [
                'Provides the ability to include related resources/models.',
                'Available includes are shown on the individual resources.',
                'Example: includes=relatedResource1::columns=column1,column2,relatedResource2::orderBy=column1'
            ],
            'page' => 'Allows you to paginate the results.',
            'perPage' => 'Allows you to set the number of items per page returned.',
            'columnData' => 'Shows only the resource parameter data types.',
            'formData' => 'Shows only the resource form data.',
            'dataOnly' => 'Returns only the data requested.',
            'fullInfo' => 'Returns all information on the resource, including pagination, links, documentation, etc.'
        ], $documentation['defaultParametersForRoutes']);
    }

    private function assertParameterDataTypesPart(array $documentation): void
    {
        $parameterDataTypes = $documentation['parameterDataTypes'];

        $this->assertEquals([
            '"parameterDataTypes" references how you can access a given resource parameter.',
            'For "databaseDataTypes" "contains-" triggers functionality that looks for the word after the hyphen in the given data type name of the resource parameter. ex: "contains-int" finds the word "int" in the database data type of "bigint", which determines that resource parameter to be an parameter data type of int.'
        ], $parameterDataTypes['notes']);

        $this->assertCount(7, $parameterDataTypes);
        $this->assertArrayHasKey('notes', $parameterDataTypes);
        $this->assertArrayHasKey('options', $parameterDataTypes);
        $this->assertArrayHasKey('int', $parameterDataTypes);
        $this->assertArrayHasKey('string', $parameterDataTypes);
        $this->assertArrayHasKey('json', $parameterDataTypes);
        $this->assertArrayHasKey('date', $parameterDataTypes);
        $this->assertArrayHasKey('float', $parameterDataTypes);

        $this->assertOptionsPart($parameterDataTypes['options']);
        $this->assertIntPart($parameterDataTypes['int']);
        $this->assertStringPart($parameterDataTypes['string']);
        $this->assertJsonPart($parameterDataTypes['json']);
        $this->assertDatePart($parameterDataTypes['date']);
        $this->assertFloatPart($parameterDataTypes['float']);
    }

    private function assertOptionsPart(array $options): void
    {
        $this->assertEquals([
            '=' => ['equal', 'e', '='],
            '>' => ['greaterthan', 'gt', '>'],
            '>=' => ['greaterthanorequal', 'gte', '>='],
            '<' => ['lessthan', 'lt', '<'],
            '<=' => ['lessthanorequal', 'lte', '<='],
            'bt' => ['between', 'bt'],
            'in' => ['in'],
            'notin' => ['notin', 'ni']
        ], $options);
    }

    private function assertIntPart(array $intPart): void
    {
        $this->assertEquals([
            'notes' => ['Only ints, whole numbers are allowed.'],
            'databaseDataTypes' => [
                'contains-int', 'tinyint', 'smallint', 'mediumint', 'int', 'bigint'
            ],
            'documentation' => [
                'arrayOptions' => 'in,notin,between',
                'arrayExamples' => [
                    'in' => ['intParameter=1,2,3', 'intParameter=1,2,3::in'],
                    'notin' => ['intParameter=1,2,3::notin'],
                    'between' => ['intParameter=1,3::between']
                ],
                'singleOptions' => '>,>=,<,<=,='
            ]
        ], $intPart);
    }

    private function assertStringPart(array $stringPart): void
    {
        $this->assertEquals(['string'], $stringPart);
    }

    private function assertJsonPart(array $jsonPart): void
    {
        $this->assertEquals(['json'], $jsonPart);
    }

    private function assertDatePart(array $datePart): void
    {
        $this->assertEquals(['date'], $datePart);
    }

    private function assertFloatPart(array $floatPart): void
    {
        $this->assertEquals(['float'], $floatPart);
    }
}
//         2 => "<="
//       ]
//       "bt" => array:2 [
//         0 => "between"
//         1 => "bt"
//       ]
//       "in" => array:1 [
//         0 => "in"
//       ]
//       "notin" => array:2 [
//         0 => "notin"
//         1 => "ni"
//       ]
//     ]
//     "int" => array:3 [
//       "notes" => array:1 [
//         0 => "Only ints, whole numbers are allowed."
//       ]
//       "databaseDataTypes" => array:6 [
//         0 => "contains-int"
//         1 => "tinyint"
//         2 => "smallint"
//         3 => "mediumint"
//         4 => "int"
//         5 => "bigint"
//       ]
//       "documentation" => array:3 [
//         "arrayOptions" => "in,notin,between"
//         "arrayExamples" => array:3 [
//           "in" => array:2 [
//             0 => "intParameter=1,2,3"
//             1 => "intParameter=1,2,3::in"
//           ]
//           "notin" => array:1 [
//             0 => "intParameter=1,2,3::notin"
//           ]
//           "between" => array:1 [
//             0 => "intParameter=1,3::between"
//           ]
//         ]
//         "singleOptions" => ">,>=,<,<=,="
//       ]
//     ]
//     "string" => "string"
//     "json" => "json"
//     "date" => "date"
//     "float" => "float"
//   ]
// ]
