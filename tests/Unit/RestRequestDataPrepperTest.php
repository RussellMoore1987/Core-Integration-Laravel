<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use Illuminate\Http\Request;
use Tests\TestCase;

// ! Start here ****************************************************************** read over file and test readability, test coverage, test organization, tests grouping, go one by one (I have a stash of tests**** EndpointValidatorTest.php)
// testing what I am suppose to
// add test groups
// remove duplicates

class RestRequestDataPrepperTest extends TestCase
{
    // TODO: set these with $preppedData and $expectedResponse
    protected $requestData = [
        'resource' => 'projects',
        'start_date' => '2020-02-28',
        'id' => 33,
        'title' => 'Gogo!!!'
    ];
    protected $expectedResponse;

    /**
     * @group get
     */
    public function test_rest_request_data_prepper_returns_expected_result()
    {
        $preppedData = $this->prepareData($this->requestData);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => 33,
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!'
            ],
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $preppedData);
    }

    public function test_rest_request_data_prepper_returns_expected_result_no_resourceId()
    {
        unset($this->requestData['id']);
        $this->requestData['ham'] = 33;
        $preppedData = $this->prepareData($this->requestData);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => '',
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!',
                'ham' => 33
            ],
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $preppedData);
    }

    public function test_rest_request_data_prepper_returns_expected_result_empty_parameters()
    {
        unset($this->requestData['start_date']);
        unset($this->requestData['title']);
        $preppedData = $this->prepareData($this->requestData);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => 33,
            'parameters' => [],
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $preppedData);
    }

    public function test_rest_request_data_prepper_returns_expected_result_no_parameters()
    {
        $preppedData = $this->prepareData([], 'api/v1');

        $expectedResponse = [
            'resource' => 'index',
            'resourceId' => '',
            'parameters' => [],
            'url' => 'http://localhost/api/v1',
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $preppedData);
    }

    /**
     * @dataProvider resourceIdProvider
     */
    public function test_rest_request_data_prepper_returns_expected_result_different_resource_id_text($resourceIdText)
    {
        unset($this->requestData['id']);
        $this->requestData[$resourceIdText] = 33;
        $preppedData = $this->prepareData($this->requestData);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => 33,
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!'
            ],
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }
    public function resourceIdProvider()
    {
        return [
            'id' => ['id'],
            'resourceId' => ['resourceId'],
            'resource_id' => ['resource_id'],
        ];
    }

    /**
     * @dataProvider requestMethodProvider
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_rest_request_data_prepper_returns_expected_result_using_different_http_methods($methodText)
    {
        $preppedData = $this->prepareData($this->requestData, 'api/v1/projects', $methodText);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => 33,
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!'
            ],
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => $methodText
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }
    public function requestMethodProvider()
    {
        return [
            'GET' => ['GET'],
            'POST' => ['POST'],
            'PUT' => ['PUT'],
            'PATCH' => ['PATCH'],
        ];
    }

    public function test_rest_request_data_prepper_returns_expected_result_random_parameters()
    {
        $preppedData = $this->prepareData([
            'resource' => '$%#@',
            'resourceId' => '1,2,6,8,99,22',
            '33' => '\'',
            '::' => 'pwer',
            '\'' => 33,
            '{}' => [],
            '[]' => '1,2,3,4,5,6,7',
        ]);

        $expectedResponse = [
            'resource' => '$%#@',
            'resourceId' => '1,2,6,8,99,22',
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => 'GET',
            'parameters' => [
                '33' => '\'',
                '::' => 'pwer',
                '\'' => 33,
                '{}' => [],
                '[]' => '1,2,3,4,5,6,7',
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    protected function prepareData(array $parameters = [], $url = 'api/v1/projects', $method = 'GET')
    {
        $request = Request::create($url, $method, $parameters);
        $restRequestDataPrepper = new RestRequestDataPrepper($request);
        $restRequestDataPrepper->prep();
        $preppedData = $restRequestDataPrepper->getPreppedData();

        return $preppedData;
    }
}
