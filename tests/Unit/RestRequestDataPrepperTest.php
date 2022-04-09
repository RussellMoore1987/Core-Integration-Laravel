<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use Illuminate\Http\Request;
use Tests\TestCase;

class RestRequestDataPrepperTest extends TestCase
{
    public function test_rest_request_data_prepper_returns_expected_result()
    {
        $preppedData = $this->prepareData([
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            'id' => 33,
            'title' => 'Gogo!!!'
        ]);

        $expectedResponse = [
            'endpoint' => 'projects',
            'endpointId' => 33,
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!'
            ],
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_rest_request_data_prepper_returns_expected_result_no_endpointId()
    {
        $preppedData = $this->prepareData([
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            'ham' => 33,
            'title' => 'Gogo!!!'
        ]);

        $expectedResponse = [
            'endpoint' => 'projects',
            'endpointId' => '',
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!',
                'ham' => 33
            ],
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_rest_request_data_prepper_returns_expected_result_empty_parameters()
    {
        $preppedData = $this->prepareData([
            'endpoint' => 'projects',
            'id' => 33,
        ]);

        $expectedResponse = [
            'endpoint' => 'projects',
            'endpointId' => 33,
            'parameters' => [],
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_rest_request_data_prepper_returns_expected_result_no_parameters()
    {
        $preppedData = $this->prepareData();

        $expectedResponse = [
            'endpoint' => 'index',
            'endpointId' => '',
            'parameters' => [],
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    /**
     * @dataProvider endpointIdProvider
     */
    public function test_rest_request_data_prepper_returns_expected_result_different_endpoint_id_text($endpointIdText)
    {
        $preppedData = $this->prepareData([
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            $endpointIdText => 33,
            'title' => 'Gogo!!!'
        ]);

        $expectedResponse = [
            'endpoint' => 'projects',
            'endpointId' => 33,
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!'
            ],
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }
    public function endpointIdProvider()
    {
        return [
            'id' => ['id'],
            'endpointId' => ['endpointId'],
            'endpoint_id' => ['endpoint_id'],
        ];
    }

    /**
     * @dataProvider httpMethodProvider
     */
    public function test_rest_request_data_prepper_returns_expected_result_using_different_http_methods($methodText)
    {
        $preppedData = $this->prepareData([
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            'endpointId' => 33,
            'title' => 'Gogo!!!'
        ], 'api/v1/projects', $methodText);

        $expectedResponse = [
            'endpoint' => 'projects',
            'endpointId' => 33,
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!'
            ],
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => $methodText
        ];

        $this->assertEquals($expectedResponse,$preppedData);
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

    public function test_rest_request_data_prepper_returns_expected_result_random_parameters()
    {
        $preppedData = $this->prepareData([
            'endpoint' => '$%#@',
            'endpointId' => '1,2,6,8,99,22',
            '33' => '\'',
            '::' => 'pwer',
            '\'' => 33,
            '{}' => [],
            '[]' => '1,2,3,4,5,6,7',
        ]);

        $expectedResponse = [
            'endpoint' => '$%#@',
            'endpointId' => '1,2,6,8,99,22',
            'url' => 'http://localhost/api/v1/projects',
            'httpMethod' => 'GET',
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
