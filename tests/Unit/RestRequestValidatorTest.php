<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\ClassDataProvider;
use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidatorFactory;
use App\CoreIntegrationApi\RestApi\RestRequestValidator;
use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use Illuminate\Http\Exceptions\HttpResponseException;

class RestRequestValidatorTest extends TestCase
{
    // ===============================================================================================
    // these tests for the most part should only test what the validator class it's self produces***
    // ===============================================================================================

    /**
     * @dataProvider ReturnsExpectedResultProvider
     */
    public function test_rest_request_validator_returns_expected_result($httpMethod, $expectedAcceptedParameters)
    {
        $validatedMetaData = $this->validateRequest([
            'resource' => 'projects',
            'id' => 33,
            'title' => 'Test Project',
        ], 'api/v1/projects', $httpMethod);

        $this->assertArrayHasKey('endpointData', $validatedMetaData);
        $this->assertArrayHasKey('extraData', $validatedMetaData);
        $this->assertArrayHasKey('acceptableParameters', $validatedMetaData['extraData']);
        $this->assertArrayHasKey('availableMethodCalls', $validatedMetaData['extraData']);
        $this->assertArrayHasKey('availableIncludes', $validatedMetaData['extraData']);
        $this->assertArrayHasKey('rejectedParameters', $validatedMetaData);
        $this->assertArrayHasKey('acceptedParameters', $validatedMetaData);
        $this->assertArrayHasKey('queryArguments', $validatedMetaData);

        $expectedEndpointData = [
            'resource' => 'projects',
            'resourceId' => '33',
            'endpointError' => false,
            'class' => 'App\Models\Project',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => $httpMethod,
            'resourceIdConvertedTo' => [
                'id' => 33
            ]
        ];

        $this->assertEquals($expectedEndpointData, $validatedMetaData['endpointData']);

        $this->assertEquals($expectedAcceptedParameters, $validatedMetaData['acceptedParameters']);
    }

    public function ReturnsExpectedResultProvider()
    {
        $otherAcceptedParameters = [
            'endpoint' => [
                'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost/api/v1/',
            ],
        ];
         
        return [
            'GET' => ['GET', [
                'endpoint' => [
                    'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost/api/v1/'
                ],
                'id' => [
                    'intCoveredTo' => 33,
                    'originalIntString' => '33',
                    'comparisonOperatorCoveredTo' => '=',
                    'originalComparisonOperator' => '',
                ],
            ]],
            'POST' => ['POST', [
                'id' => 33,
                'title' => 'Test Project',
                'endpoint' => [
                    'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost/api/v1/',
                ],
            ]],
            'PUT' => ['PUT', $otherAcceptedParameters],
            'PATCH' => ['PATCH', $otherAcceptedParameters],
        ];
    }

    /**
     * @dataProvider ReturnsExpectedResultNoIdProvider
     */
    public function test_rest_request_validator_returns_expected_result_accepted_endpoint_no_id($httpMethod, $expectedAcceptedParameters)
    {
        $validatedMetaData = $this->validateRequest([
            'resource' => 'projects',
            'title' => 'Test Project',
        ], 'api/v1/projects', $httpMethod);

        $expectedEndpointData = [
            'resource' => 'projects',
            'resourceId' => '',
            'endpointError' => false,
            'class' => 'App\Models\Project',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => $httpMethod,
        ];

        $this->assertEquals($expectedEndpointData, $validatedMetaData['endpointData']);

        // $expectedAcceptedParameters = [
        //     'endpoint' => [
        //         'message' => '"projects" is a valid endpoint for this API. You can also review available endpoints at http://localhost/api/v1/'
        //     ],

        // ];

        // $httpMethod == 'POST' ? dd($httpMethod, $expectedAcceptedParameters, $validatedMetaData['acceptedParameters']) : null;

        $this->assertEquals($expectedAcceptedParameters, $validatedMetaData['acceptedParameters']);
    }

    public function ReturnsExpectedResultNoIdProvider()
    {
        $otherAcceptedParameters = [
            'endpoint' => [
                'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost/api/v1/',
            ],
        ];
         
        return [
            'GET' => ['GET', $otherAcceptedParameters],
            'POST' => ['POST', [
                'title' => 'Test Project',
                'endpoint' => [
                    'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost/api/v1/',
                ],
            ]],
            'PUT' => ['PUT', $otherAcceptedParameters],
            'PATCH' => ['PATCH', $otherAcceptedParameters],
        ];
    }

    public function test_rest_request_validator_returns_expected_result_use_generic_id_get_back_model_WorkHistoryType_id()
    {
        $validatedMetaData = $this->validateRequest([
            'resource' => 'workHistoryTypes',
            'id' => 33,
        ], 'api/v1/workHistoryTypes');

        $expectedEndpointData = [
            'resource' => 'workHistoryTypes',
            'resourceId' => 33,
            'endpointError' => false,
            'class' => 'App\Models\WorkHistoryType',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/workHistoryTypes',
            'httpMethod' => 'GET',
            'resourceIdConvertedTo' => [
                'work_history_type_id' => 33
            ]
        ];

        $this->assertEquals($expectedEndpointData, $validatedMetaData['endpointData']);
        $this->assertArrayHasKey('work_history_type_id', $validatedMetaData['acceptedParameters']);
    }

    /**
     * @dataProvider httpMethodProvider
     */
    public function test_rest_request_validator_returns_expected_result_rejected_endpoint_with_id($httpMethod)
    {
        $this->expectException(HttpResponseException::class);

        $this->validateRequest([
            'resource' => 'notProjects',
            'id' => 33,
        ], 'api/v1/notProjects', $httpMethod);
    }
    
    /**
     * @dataProvider httpMethodProvider
     */
    public function test_rest_request_validator_returns_expected_result_rejected_endpoint_without_id($httpMethod)
    {
        $this->expectException(HttpResponseException::class);

        $this->validateRequest([
            'resource' => 'notProjects',
        ], 'api/v1/notProjects', $httpMethod);
    }

    public function httpMethodProvider()
    {
        return [
            'GET' => ['GET'],
            'POST' => ['POST'],
            'PUT' => ['PUT'],
            'PATCH' => ['PATCH'],
        ];
    }
    
    /**
     * @dataProvider parameterNameProvider
     */
    public function test_rest_request_validator_returns_expected_result_default_parameters_set_in_request_validator($perPageName, $columnDataName, $formDataName)
    {
        $validatedMetaData = $this->validateRequest([
            'resource' => 'projects',
            'page' => 2,
            $perPageName => 22,
            $columnDataName => 'yes',
            $formDataName => 'Fun',
        ]);

        $expectedAcceptedParameters = [
            'endpoint' => [
              'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost/api/v1/'
            ],
            'page' => 2,
            'perPage' => 22,
            'columnData' => [
              'value' => 'yes',
              'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter data for this resource/endpoint',
            ],
            'formData' => [
              'value' => 'Fun',
              'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter form data for this resource/endpoint',
            ],
        ];

        $this->assertEquals($expectedAcceptedParameters, $validatedMetaData['acceptedParameters']);
    }

    public function parameterNameProvider()
    {
        return [
            'snake_case_parameter' => ['per_page', 'column_data', 'form_data'],
            'camelCaseParameter' => ['perPage', 'columnData', 'formData'],
        ];
    }

    /**
     * @dataProvider parameterValueProvider
     */
    public function test_rest_request_validator_returns_expected_result_default_parameters_rejected($pageValue, $perPageValue)
    {
        $validatedMetaData = $this->validateRequest([
            'resource' => 'projects',
            'page' => $pageValue,
            'perPage' => $perPageValue,
        ]);

        $expectedRejectedParameters = [
            'page' => [
                'value' => $pageValue,
                'parameterError' => 'This parameter\'s value must be an int.',
            ],
            'perPage' => [
                'value' => $perPageValue,
                'parameterError' => 'This parameter\'s value must be an int.',
            ],
        ];

        $this->assertEquals($expectedRejectedParameters, $validatedMetaData['rejectedParameters']);
    }

    public function parameterValueProvider()
    {
        return [
            'float values' => [2.6, 22.2],
            'string values' => ['sam', 'fun'],
        ];
    }

    public function test_rest_request_validator_returns_expected_result_non_acceptable_parameters()
    {
        $validatedMetaData = $this->validateRequest([
            'resource' => 'projects',
            'pageJoe' => 2,
            'Ham' => 22.99,
            '' => 'yes',
            'array' => [],
        ]);

        $expectedRejectedParameters = [
            'pagejoe' => [
                'value' => 2,
                'parameterError' => 'This is an invalid parameter for this resource/endpoint.',  
            ],
            'ham' => [
                'value' => 22.99,
                'parameterError' => 'This is an invalid parameter for this resource/endpoint.', 
            ],
            '' => [
                'value' => 'yes',
                'parameterError' => 'This is an invalid parameter for this resource/endpoint.',  
            ],
            'array' => [
                'value' => [],
                'parameterError' => 'This is an invalid parameter for this resource/endpoint.',   
            ]
        ];

        $this->assertEquals($expectedRejectedParameters, $validatedMetaData['rejectedParameters']);
    }

    protected function validateRequest(array $parameters = [], $url = 'api/v1/projects', $method = 'GET')
    {
        $request = Request::create($url, $method, $parameters);
        $restRequestDataPrepper = new RestRequestDataPrepper($request);
        $validatorDataCollector = App::make(ValidatorDataCollector::class);
        $classDataProvider = App::make(ClassDataProvider::class);
        $httpMethodTypeValidatorFactory = App::make(HttpMethodTypeValidatorFactory::class);

        $restRequestValidator = new RestRequestValidator($restRequestDataPrepper, $validatorDataCollector, $classDataProvider, $httpMethodTypeValidatorFactory);

        return $restRequestValidator->validate();
    }
}
