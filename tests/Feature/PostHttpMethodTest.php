<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PostHttpMethodTest extends TestCase
{
    use DatabaseTransactions;

    private $projects = [];

    protected function setUp(): void
    {
        parent::setUp();

    }

    /**
     * @group db
     * ? https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422#:~:text=The%20HyperText%20Transfer%20Protocol%20(HTTP,to%20process%20the%20contained%20instructions.
     */
    public function test_post_request_return_unprocessable_entity_422_response_because_post_model_parameters_not_valid() : void
    {
        $response = $this->post('/api/v1/projects',[
            'title' => '', // this throws the error
            'roles' => 'Dev',
        ]);

        $responseArray = json_decode($response->content(), true);

        $expectedResponse = [
            'error' => 'Validation failed',
            'errors' => [
              'title' => [
                0 => 'The title field is required.'
              ]
            ],
            'message' => 'Validation failed, resend request after adjustments have been made.',
            'status_code' => 422,
        ];

        $this->assertEquals($expectedResponse,$responseArray);
    }

}