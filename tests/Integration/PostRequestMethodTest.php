<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PostRequestMethodTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @group post
     * @group rest
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
            'statusCode' => 422,
        ];

        $this->assertEquals($expectedResponse,$responseArray);
    }

    /**
     * @dataProvider parameterToValidateProvider
     * @group post
     * @group rest
     * @group db
     */
    public function test_post_request_creates_new_record($resource, $primaryKey, $parameters, $classPath) : void
    {
        $response = $this->post("/api/v1/$resource", $parameters);

        $response->assertStatus(201);

        $responseArray = json_decode($response->content(), true);

        $newRecordId = $responseArray['newRecord'][$primaryKey];
        $expectedResponse = [
            'status' => 201,
            'newRecord' => $responseArray['newRecord'],
            'newRecordLocation' => "http://localhost:8000/api/v1/{$resource}/{$newRecordId}",
        ];

        $resourceObjects = $classPath::all();

        $this->assertEquals(1,$resourceObjects->count());
        $this->assertEquals($expectedResponse,$responseArray);
    }

    public function parameterToValidateProvider() : array
    {
        return [
            'projects' => ['projects', 'id', ['title' => 'Test Project'], 'App\Models\Project'],
            'categories' => ['categories', 'id', ['name' => 'Test Category'], 'App\Models\Category'],
            'workHistoryTypes' => ['workHistoryTypes', 'work_history_type_id', ['name' => 'Test WorkHistoryType', 'icon' => 'fa-user'], 'App\Models\WorkHistoryType'],
        ];
    }

}