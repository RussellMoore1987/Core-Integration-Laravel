<?php

namespace Tests\Feature;

use Tests\TestCase;

class GetRequestMethodTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @group getMethod
     * ? https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422#:~:text=The%20HyperText%20Transfer%20Protocol%20(HTTP,to%20process%20the%20contained%20instructions.
     */
    public function test_get_request_return_unprocessable_entity_422_response_because_project_parameters_not_valid() : void
    {
        $response = $this->get('/api/v1/projects?pageJoe=2&Ham=22.99&array=[]');

        $response->assertStatus(422);

        $responseArray = json_decode($response->content(), true);
        
        $expectedResponse = [
            'error' => 'Validation Failed',
            'rejectedParameters' => [
                'pagejoe' => [
                    'value' => 2,
                    'parameterError' => 'This is an invalid parameter for this resource/endpoint.',
                ],
                'ham' => [
                    'value' => 22.99,
                    'parameterError' => 'This is an invalid parameter for this resource/endpoint.',
                ],
                'array' => [
                    'value' => '[]',
                    'parameterError' => 'This is an invalid parameter for this resource/endpoint.',
                ]
            ],
            'acceptedParameters' => [
                'endpoint' => [
                    'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost:8000/api/v1/'
                ]
            ],
            'message' => 'Validation failed, resend request after adjustments have been made.',
            'status_code' => 422,
        ];

        $this->assertEquals($expectedResponse,$responseArray);
    }

    /**
     * @dataProvider parameterValueProvider
     * @group getMethod
     */
    public function test_get_request_returns_expected_result_default_parameters_rejected($pageValue, $perPageValue)
    {
        $response = $this->get("/api/v1/projects?page={$pageValue}&perPage={$perPageValue}");

        $response->assertStatus(422);

        $responseArray = json_decode($response->content(), true);

        $expectedResponse = [
            'error' => 'Validation Failed',
            'rejectedParameters' => [
                'page' => [
                    'value' => $pageValue,
                    'parameterError' => 'This parameter\'s value must be an int.',
                ],
                'perPage' => [
                    'value' => $perPageValue,
                    'parameterError' => 'This parameter\'s value must be an int.',
                ],
            ],
            'acceptedParameters' => [
                'endpoint' => [
                    'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost:8000/api/v1/'
                ]
            ],
            'message' => 'Validation failed, resend request after adjustments have been made.',
            'status_code' => 422,
        ];

        $this->assertEquals($expectedResponse,$responseArray);
    }

    public function parameterValueProvider()
    {
        return [
            'float values' => [2.6, 22.2],
            'string values' => ['sam', 'fun'],
        ];
    }
}