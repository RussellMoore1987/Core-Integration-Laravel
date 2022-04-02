<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use App\CoreIntegrationApi\RestApi\RestRequestValidator;
use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;

class RestRequestValidatorTest extends TestCase
{
    // ! start here ******************************************* test $this->extraData in first test and in not good endpoint, fix up code, fix context with request info
    // TODO: switch everything to one type of case***

    // TODO: dynamic index ex: api/v1, api/v2, api/rest/v1, api/context/v3

    // TODO: parameters validate length, setting validation date, string, int, float ect *****

    // TODO: Test in other class
    // classDataProvider, also add it to RequestValidator
        // acceptableParameters
        // availableMethodCalls
        // availableIncludes




    // these tests for the most part should only test what the validator class it's self produces
    /**
     * @dataProvider httpMethodProvider
     */
    public function test_rest_request_data_prepper_returns_expected_result($url, $httpMethod)
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            'id' => 33,
            'title' => 'Gogo!!!'
        ], $url, $httpMethod);

        $this->assertArrayHasKey('endpointData', $validatedMetaData);
        $this->assertArrayHasKey('extraData', $validatedMetaData);
        $this->assertArrayHasKey('rejectedParameters', $validatedMetaData);
        $this->assertArrayHasKey('acceptedParameters', $validatedMetaData);
        $this->assertArrayHasKey('queryArguments', $validatedMetaData);

        $expectedEndpointData = [
            'endpoint' => 'projects',
            'endpointId' => 33,
            'endpointError' => false,
            'class' => 'App\Models\Project',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => $httpMethod,
            'endpointIdConvertedTo' => [
                'id' => 33
            ]
        ];

        $this->assertEquals($expectedEndpointData, $validatedMetaData['endpointData']);

        $expectedEndpoint = [
          'message' => '"projects" is a valid endpoint for this API. You can also review available endpoints at http://localhost/api/v1/'
        ];

        $this->assertEquals($expectedEndpoint, $validatedMetaData['acceptedParameters']['endpoint']);
    }


    /**
     * @dataProvider httpMethodProvider
     */
    public function test_rest_request_data_prepper_returns_expected_result_accepted_endpoint_no_id($url, $httpMethod)
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            'title' => 'Gogo!!!'
        ], $url, $httpMethod);

        $this->assertArrayHasKey('endpointData', $validatedMetaData);
        $this->assertArrayHasKey('extraData', $validatedMetaData);
        $this->assertArrayHasKey('rejectedParameters', $validatedMetaData);
        $this->assertArrayHasKey('acceptedParameters', $validatedMetaData);
        $this->assertArrayHasKey('queryArguments', $validatedMetaData);

        $expectedEndpointData = [
            'endpoint' => 'projects',
            'endpointId' => '',
            'endpointError' => false,
            'class' => 'App\Models\Project',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => $httpMethod,
        ];

        $this->assertEquals($expectedEndpointData, $validatedMetaData['endpointData']);

        $expectedAcceptedParameters = [
            'endpoint' => [
                'message' => '"projects" is a valid endpoint for this API. You can also review available endpoints at http://localhost/api/v1/'
            ],
            'start_date' => [
                'dateCoveredTo' => '2020-02-28 00:00:00',
                'originalDate' => '2020-02-28',
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => ''
            ]
        ];

        $this->assertEquals($expectedAcceptedParameters, $validatedMetaData['acceptedParameters']);
    }

    public function httpMethodProvider()
    {
        return [
            'GET' => ['api/v1/projects', 'GET'],
            'POST' => ['api/v1/projects', 'POST'],
            'PUT' => ['api/v1/projects', 'PUT'],
            'PATCH' => ['api/v1/projects', 'PATCH'],
        ];
    }

    /**
     * @dataProvider httpMethodNotGoodEndpointProvider
     */
    public function test_rest_request_data_prepper_returns_expected_result_rejected_endpoint_with_id($url, $httpMethod)
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'notProjects',
            'start_date' => '2020-02-28',
            'id' => 33,
            'title' => 'Gogo!!!'
        ], $url, $httpMethod);

        $this->assertArrayHasKey('endpointData', $validatedMetaData);
        $this->assertArrayHasKey('extraData', $validatedMetaData);
        $this->assertArrayHasKey('rejectedParameters', $validatedMetaData);
        $this->assertArrayHasKey('acceptedParameters', $validatedMetaData);
        $this->assertArrayHasKey('queryArguments', $validatedMetaData);

        $expectedEndpointData = [
            'endpoint' => 'notProjects',
            'endpointId' => 33,
            'endpointError' => true,
            'class' => null,
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/notProjects',
            'httpMethod' => $httpMethod,
        ];

        $this->assertEquals($expectedEndpointData, $validatedMetaData['endpointData']);

        $expectedEndpoint = [
            'message' => '"notProjects" is not a valid endpoint for this API. Please review available endpoints at http://localhost/api/v1/'
        ];

        $expectedEndpointId = [
            'message' => '"notProjects" is not a valid endpoint for this API, therefore the endpoint ID is invalid as well. Please review available endpoints at http://localhost/api/v1/',
            'value' => 33
        ];

        $this->assertEquals($expectedEndpoint, $validatedMetaData['rejectedParameters']['endpoint']);
        $this->assertEquals($expectedEndpointId, $validatedMetaData['rejectedParameters']['endpointId']);
    }
    
    /**
     * @dataProvider httpMethodNotGoodEndpointProvider
     */
    public function test_rest_request_data_prepper_returns_expected_result_rejected_endpoint_without_id($url, $httpMethod)
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'notProjects',
            'start_date' => '2020-02-28',
            'title' => 'Gogo!!!'
        ], $url, $httpMethod);

        $this->assertArrayHasKey('endpointData', $validatedMetaData);
        $this->assertArrayHasKey('extraData', $validatedMetaData);
        $this->assertArrayHasKey('rejectedParameters', $validatedMetaData);
        $this->assertArrayHasKey('acceptedParameters', $validatedMetaData);
        $this->assertArrayHasKey('queryArguments', $validatedMetaData);

        $expectedEndpointData = [
            'endpoint' => 'notProjects',
            'endpointId' => '',
            'endpointError' => true,
            'class' => null,
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/notProjects',
            'httpMethod' => $httpMethod,
        ];

        $this->assertEquals($expectedEndpointData, $validatedMetaData['endpointData']);

        $expectedEndpoint = [
            'message' => '"notProjects" is not a valid endpoint for this API. Please review available endpoints at http://localhost/api/v1/'
        ];

        $this->assertEquals($expectedEndpoint, $validatedMetaData['rejectedParameters']['endpoint']);
        $this->assertArrayNotHasKey('endpointId', $validatedMetaData['rejectedParameters']);
    }

    public function httpMethodNotGoodEndpointProvider()
    {
        return [
            'GET' => ['api/v1/notProjects', 'GET'],
            'POST' => ['api/v1/notProjects', 'POST'],
            'PUT' => ['api/v1/notProjects', 'PUT'],
            'PATCH' => ['api/v1/notProjects', 'PATCH'],
        ];
    }
    
    /**
     * @dataProvider parameterNameProvider
     */
    public function test_rest_request_data_prepper_returns_expected_result_parameters_set_in_request_validator($perPageName, $columnDataName, $formDataName)
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'projects',
            'page' => 2,
            $perPageName => 22,
            $columnDataName => 'yes',
            $formDataName => 'yes',
        ]);

        $expectedAcceptedParameters = [
            'endpoint' => [
              'message' => '"projects" is a valid endpoint for this API. You can also review available endpoints at http://localhost/api/v1/'
            ],
            'page' => 2,
            'perPage' => 22,
            'columnData' => [
              'value' => 'yes',
              'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter data for this endpoint',
            ],
            'formData' => [
              'value' => 'yes',
              'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter form data for this endpoint',
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
    public function test_rest_request_data_prepper_returns_expected_result_parameters_rejected($pageValue, $perPageValue)
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'projects',
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

    public function test_rest_request_data_prepper_returns_expected_result_non_expectable_parameters()
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'projects',
            'pageJoe' => 2,
            'Ham' => 22.99,
            '' => 'yes',
            'array' => [],
        ]);

        $expectedRejectedParameters = [
            'pagejoe' => [
                'value' => 2,
                'parameterError' => 'This is an invalid parameter for this endpoint.',  
            ],
              'ham' => [
                'value' => 22.99,
                'parameterError' => 'This is an invalid parameter for this endpoint.', 
              ],
              '' => [
                'value' => 'yes',
                'parameterError' => 'This is an invalid parameter for this endpoint.',  
              ],
              'array' => [
                'value' => [],
                'parameterError' => 'This is an invalid parameter for this endpoint.',   
              ]
        ];

        $this->assertEquals($expectedRejectedParameters, $validatedMetaData['rejectedParameters']);
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
