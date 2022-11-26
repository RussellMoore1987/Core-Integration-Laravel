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
     * @integration
     * ? https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422#:~:text=The%20HyperText%20Transfer%20Protocol%20(HTTP,to%20process%20the%20contained%20instructions.
     */
    public function test_post_request_return_unprocessable_entity_422_response_because_project_model_parameters_not_valid() : void
    {
        $response = $this->post('/api/v1/projects',[
            'title' => '', // this throws the error
            'roles' => 'Dev',
        ]);

        $response->assertStatus(422);

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

    /**
     * @integration
     * @dataProvider parameterToValidateProvider
     */
    public function test_post_request_creates_new_record($endpoint, $parameters, $classPath) : void
    {
        $response = $this->post("/api/v1/$endpoint", $parameters);

        $response->assertStatus(201);

        $responseArray = json_decode($response->content(), true);

        $expectedResponse = [
            'error' => 'Validation failed',
            'recordId' => 66,
            'message' => 'Validation failed, resend request after adjustments have been made.',
            'status_code' => 422,
        ];

        $classObjects = $classPath::all();

        $this->assertEquals(1,$classObjects->count());
        $this->assertEquals($expectedResponse,$responseArray);
    }

    public function parameterToValidateProvider()
    {
        return [
            'projects' => ['projects', ['title' => 'Test Project'], 'App\Models\Project'],
            'categories' => ['categories', ['name' => 'Test Category'], 'App\Models\Category'],
            'workHistoryTypes' => ['WorkHistoryTypes', ['name' => 'Test WorkHistoryType', 'icon' => 'fa-user'], 'App\Models\WorkHistoryType'],
        ];
    }

}