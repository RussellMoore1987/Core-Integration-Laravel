<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use App\CoreIntegrationApi\RestApi\RestRequestValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class RestRequestValidatorTest extends TestCase
{
    // TODO: Test
    // data collection, what it validates***, all data returned, endpoint, endpointId, column validation, default parameters, other
    // other validation done else were***

    public function test_context_request_data_prepper_returns_expected_result()
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            'id' => 33,
            'title' => 'Gogo!!!'
        ]);

        dd($validatedMetaData);


        [
            "endpointData" => array:8 [
              "endpoint" => "projects"
              "endpointId" => 33
              "endpointError" => false
              "class" => "App\Models\Project"
              "indexUrl" => "http://"
              "url" => "http://localhost:8000"
              "httpMethod" => "GET"
              "endpointIdConvertedTo" => array:1 [
                "id" => 33
              ]
            ]
            "extraData" => array:3 [
              "acceptableParameters" => array:15 [
                "id" => array:7 [
                  "field" => "id"
                  "type" => "bigint unsigned"
                  "null" => "no"
                  "key" => "pri"
                  "default" => null
                  "extra" => "auto_increment"
                  "api_data_type" => "int"
                ]
                "title" => array:7 [
                  "field" => "title"
                  "type" => "varchar(75)"
                  "null" => "no"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "string"
                ]
                "roles" => array:7 [
                  "field" => "roles"
                  "type" => "varchar(50)"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "string"
                ]
                "client" => array:7 [
                  "field" => "client"
                  "type" => "varchar(50)"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "string"
                ]
                "description" => array:7 [
                  "field" => "description"
                  "type" => "varchar(255)"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "string"
                ]
                "content" => array:7 [
                  "field" => "content"
                  "type" => "json"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "json"
                ]
                "video_link" => array:7 [
                  "field" => "video_link"
                  "type" => "varchar(255)"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "string"
                ]
                "code_link" => array:7 [
                  "field" => "code_link"
                  "type" => "varchar(255)"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "string"
                ]
                "demo_link" => array:7 [
                  "field" => "demo_link"
                  "type" => "varchar(255)"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "string"
                ]
                "start_date" => array:7 [
                  "field" => "start_date"
                  "type" => "timestamp"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "date"
                ]
                "end_date" => array:7 [
                  "field" => "end_date"
                  "type" => "timestamp"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "date"
                ]
                "is_published" => array:7 [
                  "field" => "is_published"
                  "type" => "tinyint"
                  "null" => "no"
                  "key" => ""
                  "default" => "0"
                  "extra" => ""
                  "api_data_type" => "int"
                ]
                "created_at" => array:7 [
                  "field" => "created_at"
                  "type" => "timestamp"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "date"
                ]
                "updated_at" => array:7 [
                  "field" => "updated_at"
                  "type" => "timestamp"
                  "null" => "yes"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "date"
                ]
                "budget" => array:7 [
                  "field" => "budget"
                  "type" => "decimal(8,2)"
                  "null" => "no"
                  "key" => ""
                  "default" => null
                  "extra" => ""
                  "api_data_type" => "float"
                ]
              ]
              "availableMethodCalls" => array:3 [
                0 => "pluse1_5"
                1 => "pluse1_5"
                2 => "newTitle"
              ]
              "availableIncludes" => array:3 [
                0 => "images"
                1 => "tags"
                2 => "categories"
              ]
            ]
            "rejectedParameters" => []
            "acceptedParameters" => array:3 [
              "endpoint" => array:1 [
                "messsage" => ""projects" is a valid endpoint for this API. You can also 
          review available endpoints at http://"
              ]
              "start_date" => array:4 [
                "dateCoveredTo" => "2020-02-28 00:00:00"
                "originalDate" => "2020-02-28"
                "comparisonOperatorCoveredTo" => "="
                "originalComparisonOperator" => ""
              ]
              "id" => array:4 [
                "intCoveredTo" => 33
                "originalIntString" => 33
                "comparisonOperatorCoveredTo" => "="
                "originalComparisonOperator" => ""
              ]
            ]
            "queryArguments" => array:2 [
              "start_date" => array:5 [
                "dataType" => "date"
                "columnName" => "start_date"
                "date" => "2020-02-28 00:00:00"
                "comparisonOperator" => "="
                "originalComparisonOperator" => ""
              ]
              "id" => array:5 [
                "dataType" => "int"
                "columnName" => "id"
                "int" => 33
                "comparisonOperator" => "="
                "originalComparisonOperator" => ""
              ]
            ]
          ]



        $expectedResponse = [
            'endpoint' => 'projects',
            'endpointId' => 33,
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!'
            ]
        ];

        $this->assertEquals($expectedResponse,$validatedMetaData);
    }

    

    protected function validateRequest(array $parameters = [], $url = 'api/v1/projects', $method = 'GET')
    {
        $request = Request::create($url, $method, $parameters);
        $restRequestDataPrepper = new RestRequestDataPrepper($request);

        $dataTypeDeterminerFactory = App::make(DataTypeDeterminerFactory::class);
        $parameterValidatorFactory = App::make(ParameterValidatorFactory::class);
        $validatorDataCollector = App::make(ValidatorDataCollector::class);


        $restRequestValidator = new RestRequestValidator($restRequestDataPrepper, $dataTypeDeterminerFactory, $parameterValidatorFactory, $validatorDataCollector);

        return $restRequestValidator->validate();
    }
}
