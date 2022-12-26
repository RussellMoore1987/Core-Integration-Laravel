<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use Illuminate\Http\Request;
use Tests\TestCase;

// ! Start here ****************************************************************** read over file and test readability, test coverage, test organization, tests grouping, go one by one (I have a stash of tests**** EndpointValidatorTest.php)

class RestRequestDataPrepperTest extends TestCase
{
    protected $requestData = [
        'resource' => 'projects',
        'start_date' => '2020-02-28',
        'id' => 33,
        'title' => 'Gogo!!!'
    ];
    protected $expectedResponse = [
        'resource' => 'projects',
        'resourceId' => 33,
        'parameters' => [
            'start_date' => '2020-02-28',
            'title' => 'Gogo!!!'
        ],
        'url' => 'http://localhost/api/v1/projects',
        'requestMethod' => 'GET'
    ];

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response()
    {
        $response = $this->runDataPrepper([
            'resource' => 'projects',
            'start_date' => '2020-02-28',
            'id' => 33,
            'title' => 'Gogo!!!'
        ]); Fixing RestRequestDataPrepperTest, decided to revert back to a more read able version

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

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_no_resourceId()
    {
        unset($this->requestData['id']);
        $response = $this->runDataPrepper($this->requestData);

        $this->expectedResponse['resourceId'] = '';

        $this->assertEquals($this->expectedResponse, $response);
    }

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_empty_parameters()
    {
        unset($this->requestData['start_date']);
        unset($this->requestData['title']);
        $response = $this->runDataPrepper($this->requestData);

        $this->expectedResponse['parameters'] = [];

        $this->assertEquals($this->expectedResponse, $response);
    }

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_no_parameters()
    {
        $response = $this->runDataPrepper([], 'api/v1');

        $expectedResponse = [
            'resource' => 'index',
            'resourceId' => '',
            'parameters' => [],
            'url' => 'http://localhost/api/v1',
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @dataProvider resourceIdProvider
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_different_resource_id_text($resourceIdText)
    {
        unset($this->requestData['id']);
        $this->requestData[$resourceIdText] = 33;
        $response = $this->runDataPrepper($this->requestData);

        $this->assertEquals($this->expectedResponse, $response);
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
    public function test_RestRequestDataPrepper_returns_expected_response_using_different_http_methods($methodText)
    {
        $response = $this->runDataPrepper($this->requestData, 'api/v1/projects', $methodText);

        $this->expectedResponse['requestMethod'] = $methodText;

        $this->assertEquals($this->expectedResponse, $response);
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

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_random_parameters()
    {
        $response = $this->runDataPrepper([
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
            'parameters' => [
                '33' => '\'',
                '::' => 'pwer',
                '\'' => 33,
                '{}' => [],
                '[]' => '1,2,3,4,5,6,7',
            ],
            'requestMethod' => 'GET',
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_does_not_return_excluded_variables_in_parameters_array()
    {
        $this->requestData['resourceId'] = 33;
        $this->requestData['resource_id'] = 33;
        $response = $this->runDataPrepper($this->requestData);

        $this->assertTrue(!array_key_exists('id', $response['parameters']));
        $this->assertTrue(!array_key_exists('resourceId', $response['parameters']));
        $this->assertTrue(!array_key_exists('resource_id', $response['parameters']));
        $this->assertTrue(!array_key_exists('resource', $response['parameters']));
    }

    protected function runDataPrepper(array $parameters = [], $url = 'api/v1/projects', $method = 'GET')
    {
        $request = Request::create($url, $method, $parameters);
        $restRequestDataPrepper = new RestRequestDataPrepper($request);
        $restRequestDataPrepper->prep();

        return $restRequestDataPrepper->getPreppedData();
    }
}
