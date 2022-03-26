<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use Illuminate\Http\Request;
use Tests\TestCase;

class RestRequestDataPrepperTest extends TestCase
{
    protected $request;

    public function test_context_request_data_prepper_returns_expected_result()
    {
        $request = Request::create('api/v1/projects', 'GET', [
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            'id' => 33,
            'title' => 'Gogo!!!'
        ]);
        $RestRequestDataPrepper = new RestRequestDataPrepper($request);
        $RestRequestDataPrepper->prep();
        $preppedData = $RestRequestDataPrepper->getPreppedData();

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
}
