<?php

namespace Tests\Integration;

use Tests\TestCase;

class HttpResponseExceptionRequestTest extends TestCase
{
    /**
     * @dataProvider returnsExpectedResultProvider
     * @group allRequestMethods
     * @group rest
     */
    public function test_http_request_return_404_response_invalid_endpoint($httpMethods): void
    {
        $response = $this->$httpMethods('/api/v1/notProjects/');
        $responseArray = json_decode($response->content(), true);

        $expectedResponse = [
            'error' => 'Resource/Endpoint Not Found',
            'message' => '"notProjects" is not a valid resource/endpoint for this API. Please review available resources/endpoints at http://localhost:8000/api/v1/',
            'statusCode' => 404,
        ];

        $response->assertStatus(404);
        $this->assertEquals($expectedResponse,$responseArray);
    }

    public function returnsExpectedResultProvider(): array
    {
        return [
            'get' => ['get'],
            'post' => ['post'],
            'put' => ['put'],
            'patch' => ['patch'],
            'delete' => ['delete'],
        ];
    }

    /**
     * @group get
     * @group rest
     * ? https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422#:~:text=The%20HyperText%20Transfer%20Protocol%20(HTTP,to%20process%20the%20contained%20instructions.
     */
    public function test_get_request_return_unprocessable_entity_422_response_because_project_parameters_not_valid(): void
    {
        $response = $this->get('/api/v1/projects?Ham=22.99&array=[]');

        $response->assertStatus(422);

        $responseArray = json_decode($response->content(), true);
        
        $expectedResponse = [
            'error' => 'Validation Failed',
            'rejectedParameters' => [
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
            'statusCode' => 422,
        ];

        $this->assertEquals($expectedResponse,$responseArray);
    }

    // TODO: Test
    // GET with out authentication
    // GET with authentication
    // PUT, POST, PATCH with authentication
    // PUT, POST, PATCH with out authentication, must fail
    // PUT, POST, PATCH response
    // PUT response code
    // POST response code
    // PATCH response code
    // available includes / method calls in first test in this file
    // separate test file
        // ids - the many ways
        // All data types tested, and all there veronese
            // string
            // date
            // int
            // float
            // json
        // All extra parameters
            // method call
            // includes - deep test
            // page
            // perPage
    // Not caring about casing, columns, parameters
    // form data
    // TODO: Test additional endpoint with id like post_id with id parameter
}