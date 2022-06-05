<?php

namespace Tests\Unit\HttpMethodTypeValidator;

use App\CoreIntegrationApi\ClassDataProvider;
use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators\PostHttpMethodTypeValidator;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviderFactory;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\Models\Project;
use Tests\TestCase;

class PostHttpMethodTypeValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->postHttpMethodTypeValidator = new PostHttpMethodTypeValidator();
        $this->validatorDataCollector = new ValidatorDataCollector();
        $this->requestData = $this->setRequestData();
    }

    public function test_one()
    {
        

        $httpMethodTypeValidator = $this->httpMethodTypeValidatorFactory->getFactoryItem($this->httpMethod);
        $this->validatorDataCollector = $httpMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);
    }

    protected function setRequestData()
    {
        // ! start here *********************************************** setting up tests
        $classDataProvider = new ClassDataProvider(new ParameterDataProviderFactory());
        $classInfo = $classDataProvider->getClassInfo();

        $extraData['availableMethodCalls'] = $classInfo['classParameterOptions']['availableMethodCalls'];
        $extraData['availableIncludes'] = $classInfo['classParameterOptions']['availableIncludes'];
        $extraData['acceptableParameters'] = $classInfo['classParameterOptions']['acceptableParameters'];

        $requestData = [
            'parameters' => $this->parameters,
            'extraData' => $this->extraData,
            'classObject' => $this->project = new Project(),
        ];
        // return [
        //     "parameters" => [
        //         "title" => "Test Project",
        //         "id" => 33,
        //     ],
        //     "extraData" => [
        //         // "availableMethodCalls" => [
        //         //     "pluse1_5",
        //         //     "budgetTimeTwo",
        //         //     "newTitle",
        //         // ],
        //         // "availableIncludes" => [
        //         //     "images",
        //         //     "tags",
        //         //     "categories",
        //         // ],
        //       "acceptableParameters" => [
        //             "id" => [
        //                 "field" => "id"
        //                 "type" => "bigint unsigned"
        //                 "null" => "no"
        //                 "key" => "pri"
        //                 "default" => null
        //                 "extra" => "auto_increment"
        //                 "api_data_type" => "int",
        //                 "defaultValidationRules" => [
        //                     0 => "integer",
        //                     1 => "min:0",
        //                     2 => "max:18446744073709551615",
        //                 ]
        //                 "formData" => [
        //                     "min" => 0,
        //                     "max" => 1.844674407371E+19,
        //                     "maxlength" => 20,
        //                     "type" => "number",
        //                 ],
        //             ],
        //             "title" => [
        //             "field" => "title"
        //             "type" => "varchar(75)"
        //             "null" => "no"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "string"
        //             "defaultValidationRules" => [
        //                 0 => "required"
        //             ]
        //             "formData" => [
        //                 "required" => true
        //             ]
        //             ]
        //             "roles" => [
        //             "field" => "roles"
        //             "type" => "varchar(50)"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "string"
        //             "defaultValidationRules" => []
        //             "formData" => []
        //             ]
        //             "client" => [
        //             "field" => "client"
        //             "type" => "varchar(50)"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "string"
        //             "defaultValidationRules" => []
        //             "formData" => []
        //             ]
        //             "description" => [
        //             "field" => "description"
        //             "type" => "varchar(255)"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "string"
        //             "defaultValidationRules" => []
        //             "formData" => []
        //             ]
        //             "content" => [
        //             "field" => "content"
        //             "type" => "json"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "json"
        //             "defaultValidationRules" => []
        //             "formData" => []
        //             ]
        //             "video_link" => [
        //             "field" => "video_link"
        //             "type" => "varchar(255)"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "string"
        //             "defaultValidationRules" => []
        //             "formData" => []
        //             ]
        //             "code_link" => [
        //             "field" => "code_link"
        //             "type" => "varchar(255)"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "string"
        //             "defaultValidationRules" => []
        //             "formData" => []
        //             ]
        //             "demo_link" => [
        //             "field" => "demo_link"
        //             "type" => "varchar(255)"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "string"
        //             "defaultValidationRules" => []
        //             "formData" => []
        //             ]
        //             "start_date" => [
        //             "field" => "start_date"
        //             "type" => "timestamp"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "date"
        //             "defaultValidationRules" => [
        //                 0 => "date"
        //                 1 => "after_or_equal:1970-01-01 00:00:01"
        //                 2 => "before_or_equal:2038-01-19 03:14:07"
        //             ]
        //             "formData" => [
        //                 "type" => "date"
        //                 "min" => "1970-01-01 00:00:01"
        //                 "max" => "2038-01-19 03:14:07"
        //             ]
        //             ]
        //             "end_date" => [
        //             "field" => "end_date"
        //             "type" => "timestamp"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "date"
        //             "defaultValidationRules" => [
        //                 0 => "date"
        //                 1 => "after_or_equal:1970-01-01 00:00:01"
        //                 2 => "before_or_equal:2038-01-19 03:14:07"
        //             ]
        //             "formData" => [
        //                 "type" => "date"
        //                 "min" => "1970-01-01 00:00:01"
        //                 "max" => "2038-01-19 03:14:07"
        //             ]
        //             ]
        //             "is_published" => [
        //             "field" => "is_published"
        //             "type" => "tinyint"
        //             "null" => "no"
        //             "key" => ""
        //             "default" => "0"
        //             "extra" => ""
        //             "api_data_type" => "int"
        //             "defaultValidationRules" => [
        //                 0 => "integer"
        //                 1 => "min:-128"
        //                 2 => "max:127"
        //             ]
        //             "formData" => [
        //                 "min" => 0
        //                 "max" => 1
        //                 "maxlength" => 1
        //                 "type" => "number"
        //             ]
        //             ]
        //             "created_at" => [
        //             "field" => "created_at"
        //             "type" => "timestamp"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "date"
        //             "defaultValidationRules" => [
        //                 0 => "date"
        //                 1 => "after_or_equal:1970-01-01 00:00:01"
        //                 2 => "before_or_equal:2038-01-19 03:14:07"
        //             ]
        //             "formData" => [
        //                 "type" => "date"
        //                 "min" => "1970-01-01 00:00:01"
        //                 "max" => "2038-01-19 03:14:07"
        //             ]
        //             ]
        //             "updated_at" => [
        //             "field" => "updated_at"
        //             "type" => "timestamp"
        //             "null" => "yes"
        //             "key" => ""
        //             "default" => null
        //             "extra" => ""
        //             "api_data_type" => "date"
        //             "defaultValidationRules" => [
        //                 0 => "date"
        //                 1 => "after_or_equal:1970-01-01 00:00:01"
        //                 2 => "before_or_equal:2038-01-19 03:14:07"
        //             ]
        //             "formData" => [
        //                 "type" => "date"
        //                 "min" => "1970-01-01 00:00:01"
        //                 "max" => "2038-01-19 03:14:07"
        //             ]
        //             ]
        //             "budget" => [
        //             "field" => "budget"
        //             "type" => "decimal(8,2)"
        //             "null" => "no"
        //             "key" => ""
        //             "default" => "0.00"
        //             "extra" => ""
        //             "api_data_type" => "float"
        //             "defaultValidationRules" => []
        //             "formData" => []
        //             ]
        //         ]
        //     ]
        // ]
    }

    
}
