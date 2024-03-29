<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use Illuminate\Http\Request;
use Tests\TestCase;

class RestRequestDataPrepperTest extends TestCase
{
    protected $url = 'http://localhost/api/v1/projects';
    
    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response(): void
    {
        $response = $this->runDataPrepper([
            'resource' => 'projects',
            'start_date' => '2020-02-28',
            'id' => '33',
            'title' => 'Gogo!!!'
        ]);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => '33',
            'parameters' => [
                'start_date' => '2020-02-28',
                'title' => 'Gogo!!!'
            ],
            'url' => $this->url,
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_empty_resourceId(): void
    {
        $response = $this->runDataPrepper([
            'resource' => 'projects',
            'start_date' => '2020-02-27',
            'title' => 'Gogo!!'
        ]);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => '',
            'parameters' => [
                'start_date' => '2020-02-27',
                'title' => 'Gogo!!'
            ],
            'url' => $this->url,
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_empty_parameters(): void
    {
        $response = $this->runDataPrepper([
            'resource' => 'projects',
            'id' => '33',
        ]);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => '33',
            'parameters' => [],
            'url' => $this->url,
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_no_parameters_sent_index_response(): void
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
    public function test_RestRequestDataPrepper_returns_expected_response_for_different_resource_id_text($resourceIdText): void
    {
        $response = $this->runDataPrepper([
            'resource' => 'projects',
            'start_date' => '2020-02-26',
            $resourceIdText => '33',
            'title' => 'Gogo!'
        ]);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => '33',
            'parameters' => [
                'start_date' => '2020-02-26',
                'title' => 'Gogo!'
            ],
            'url' => $this->url,
            'requestMethod' => 'GET'
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    public function resourceIdProvider(): array
    {
        return [
            'id' => ['id'],
            'resourceId' => ['resourceId'],
            'resource_id' => ['resource_id'],
        ];
    }

    /**
     * @dataProvider requestMethodProvider
     * @group allRequestMethods
     */
    public function test_RestRequestDataPrepper_returns_expected_response_using_different_http_methods($methodText): void
    {
        $response = $this->runDataPrepper([
            'resource' => 'projects',
            'start_date' => '2020-02-25',
            'id' => '33',
            'title' => 'Gogo'
        ], 'api/v1/projects', $methodText);

        $expectedResponse = [
            'resource' => 'projects',
            'resourceId' => '33',
            'parameters' => [
                'start_date' => '2020-02-25',
                'title' => 'Gogo'
            ],
            'url' => $this->url,
            'requestMethod' => $methodText
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    public function requestMethodProvider(): array
    {
        return [
            'GET' => ['GET'],
            'POST' => ['POST'],
            'PUT' => ['PUT'],
            'PATCH' => ['PATCH'],
            'DELETE' => ['DELETE'],
        ];
    }

    /**
     * @group get
     */
    public function test_RestRequestDataPrepper_returns_expected_response_for_random_parameters(): void
    {
        $response = $this->runDataPrepper([
            'resource' => '$%#@',
            'resourceId' => '1,2,6,8,99,22',
            '33' => '\'',
            '::' => 'pwer',
            '\'' => '33',
            '{}' => [],
            '[]' => '1,2,3,4,5,6,7',
        ]);

        $expectedResponse = [
            'resource' => '$%#@',
            'resourceId' => '1,2,6,8,99,22',
            'url' => $this->url,
            'parameters' => [
                '33' => '\'',
                '::' => 'pwer',
                '\'' => '33',
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
    public function test_RestRequestDataPrepper_does_not_return_excluded_variables_in_parameters_array(): void
    {
        $response = $this->runDataPrepper([
            'resource' => 'projects',
            'start_date' => '2020-02-24',
            'id' => '33',
            'resourceId' => '33',
            'resource_id' => '33',
            'title' => 'Gogo'
        ]);

        $this->assertTrue(!array_key_exists('id', $response['parameters']));
        $this->assertTrue(!array_key_exists('resourceId', $response['parameters']));
        $this->assertTrue(!array_key_exists('resource_id', $response['parameters']));
        $this->assertTrue(!array_key_exists('resource', $response['parameters']));
    }

    protected function runDataPrepper(array $parameters = [], $url = 'api/v1/projects', $method = 'GET'): array
    {
        $request = Request::create($url, $method, $parameters);
        $restRequestDataPrepper = new RestRequestDataPrepper($request);
        $restRequestDataPrepper->prep();

        return $restRequestDataPrepper->getPreppedData();
    }
}
