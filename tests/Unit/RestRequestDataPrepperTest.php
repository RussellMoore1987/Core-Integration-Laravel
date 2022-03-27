<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use Illuminate\Http\Request;
use Tests\TestCase;

class RestRequestDataPrepperTest extends TestCase
{
    public function test_context_request_data_prepper_returns_expected_result()
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
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_no_endpointId()
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
            ]
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_empty_parameters()
    {
        $preppedData = $this->prepareData([
            'endpoint' => 'projects',
            'id' => 33,
        ]);

        $expectedResponse = [
            'endpoint' => 'projects',
            'endpointId' => 33,
            'parameters' => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    public function test_context_request_data_prepper_returns_expected_result_no_parameters()
    {
        $preppedData = $this->prepareData();

        $expectedResponse = [
            'endpoint' => 'index',
            'endpointId' => '',
            'parameters' => []
        ];

        $this->assertEquals($expectedResponse,$preppedData);
    }

    /**
     * @dataProvider endpointIdProvider
     */
    public function test_context_request_data_prepper_returns_expected_result_endpointId($endpointIdText)
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
            ]
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
    public function test_context_request_data_prepper_returns_expected_result_http_methods($methodText)
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
            ]
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

    public function test_context_request_data_prepper_returns_expected_result_random_parameters()
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
        $RestRequestDataPrepper = new RestRequestDataPrepper($request);
        $RestRequestDataPrepper->prep();
        $preppedData = $RestRequestDataPrepper->getPreppedData();

        return $preppedData;
    }
}
