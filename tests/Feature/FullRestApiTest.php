<?php

namespace Tests\Feature;

use Tests\TestCase;

// TODO: Test additional endpoint with id like post_id with id parameter

class FullRestApiTest extends TestCase
{
    /**
     * @dataProvider returnsExpectedResultProvider
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_http_methods_return_404_response($httpMethods) : void
    {
        $response = $this->$httpMethods('/api/v1/notProjects/');
        $responseArray = json_decode($response->content(), true);

        $expectedResponse = [
            'error' => 'Resource/Endpoint Not Found',
            'message' => '"notProjects" is not a valid resource/endpoint for this API. Please review available resources/endpoints at http://localhost:8000/api/v1/',
            'status_code' => 404,
        ];

        $response->assertStatus(404);
        $this->assertEquals($expectedResponse,$responseArray);
    }

    public function returnsExpectedResultProvider()
    {
        return [
            'get' => ['get'],
            'post' => ['post'],
            'put' => ['put'],
            'patch' => ['patch'],
            'delete' => ['delete'],
        ];
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
}