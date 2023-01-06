<?php

namespace Tests\Integration;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GetRequestMethodTest extends TestCase
{
    use DatabaseTransactions;

    private $projects;

    /**
     * @group get
     * @group rest
     * @group db
     */
    public function test_that_what_I_get_back_is_what_I_would_exsect_from_this_endpoint_testing_json_structure() : void
    {
        $this->makeProjects();

        $projectIds = implode(',',$this->projects->pluck('id')->toArray());
        $response = $this->get("/api/v1/projects/$projectIds?per_page=1&page=2");

        $response->assertStatus(200);
        $response->assertJsonCount(17);

        // test that we have main paths
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(
                'current_page',
                'data',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
                'availableResourceParameters',
                'availableResourceParameters.parameters',
                'availableResourceParameters.parameters.info',
                'availableResourceParameters.defaultParameters.columns',
                'availableResourceParameters.defaultParameters.orderBy',
                'availableResourceParameters.defaultParameters.methodCalls',
                'availableResourceParameters.defaultParameters.methodCalls.value',
                'availableResourceParameters.defaultParameters.methodCalls.availableMethodCalls',
                'availableResourceParameters.defaultParameters.includes',
                'availableResourceParameters.defaultParameters.includes.value',
                'availableResourceParameters.defaultParameters.includes.availableIncludes',
                'availableResourceParameters.defaultParameters.page',
                'availableResourceParameters.defaultParameters.perPage',
                'availableResourceParameters.defaultParameters.columnData',
                'availableResourceParameters.defaultParameters.formData',
                'availableResourceParameters.defaultParameters.includeData',
                'availableResourceParameters.defaultParameters.methodCallData',
                'availableResourceParameters.defaultParameters.info',
                'rejectedParameters',
                'acceptedParameters',
                'endpointData'
            )
        );

        // test that parameters are set right
        $parameters = 'availableResourceParameters.parameters.';
        $response->assertJsonPath($parameters . 'id', 'int');
        $response->assertJsonPath($parameters . 'title', 'string');
        $response->assertJsonPath($parameters . 'start_date', 'date');
        $response->assertJsonPath($parameters . 'content', 'json');
        $response->assertJsonPath($parameters . 'budget', 'float');
        
        // test that endpoint data is correct
        $response->assertJsonPath('endpointData.resource', 'projects');
        $response->assertJsonPath('endpointData.resourceId', $projectIds);
        $response->assertJsonPath('endpointData.indexUrl', 'http://localhost:8000/api/v1/');
        $response->assertJsonPath('endpointData.url', 'http://localhost:8000/api/v1/projects/' . $projectIds);
        $response->assertJsonPath('endpointData.requestMethod', 'GET');
        $response->assertJsonPath('endpointData.resourceIdConvertedTo', ['id' => $projectIds]);

        // test page and per_page work
        $response->assertJsonPath('per_page', 1);
        $response->assertJsonPath('current_page', 2);

        // data count we expect back
        $response_array = json_decode($response->content(), true);
        $this->assertTrue(count($response_array['data']) <= 4);
    }

    /**
     * @group get
     * @group rest
     * @group db
     */
    public function test_return_of_one_record() : void
    {
        $this->makeProjects();

        $projectId = $this->projects[0]->id;
        $response = $this->get("/api/v1/projects/$projectId");

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->missing(
                'current_page',
                'data',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
                'availableResourceParameters',
                'availableResourceParameters.parameters',
                'availableResourceParameters.info',
                'rejectedParameters',
                'acceptedParameters',
                'endpointData'
            )->etc()
        );
    }

    /**
     * @group get
     * @group rest
     * @group db
     */
    public function test_return_404_response() : void
    {
        $this->makeProjects();

        $response = $this->get('/api/v1/projects/9999999999');

        $response->assertStatus(404);
        $response->assertJsonPath('message', 'The record with the id of 9999999999 at the "projects" endpoint was not found');
    }

    /**
     * @group get
     * @group rest
     * @group db
     */
    public function test_return_of_empty_data_set() : void
    {
        $this->makeProjects();

        $response = $this->get('/api/v1/projects/?start_date=1000-02-01');

        $response->assertStatus(200);
        $response_array = json_decode($response->content(), true);
        $this->assertTrue(count($response_array['data']) == 0);
    }

    /**
     * @group get
     * @group rest
     * @group db
     */
    public function test_return_of_column_data() : void
    {
        $this->makeProjects();

        $response = $this->get('/api/v1/projects/?columnData=yes');

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('availableResourceParameters', fn ($json) =>
                $json->hasAll(
                    'id',
                    'title',
                    'roles',
                    'client',
                    'description',
                    'content',
                    'video_link',
                    'code_link',
                    'demo_link',
                    'start_date',
                    'end_date',
                    'is_published',
                    'created_at',
                    'updated_at',
                    'budget'
                )->etc()
            )
            ->has('info', fn ($json) =>
                $json->hasAll(
                    'message',
                    'index_url'
                )
            )
        );
    }

    protected function makeProjects() : void
    {
        $content = '{"error":"error", "big":"big", "name":"Sam", "array":{"color":"red"}}';
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 1',
            'roles' => 'Developer',
            'client' => 'This is client 1',
            'description' => 'This is description 1',
            'content' => $content,
            'start_date' => '1976-05-20',
            'end_date' => '1976-07-20',
            'is_published' => 1,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 2',
            'roles' => 'UX UI Designer',
            'client' => 'This is client 2',
            'description' => 'This is description 2',
            'content' => $content,
            'start_date' => '1976-06-20',
            'end_date' => '1976-08-20',
            'is_published' => 1,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 3',
            'roles' => 'Designer',
            'client' => 'This is client 3',
            'description' => 'This is description 3',
            'content' => $content,
            'start_date' => '1976-07-20',
            'end_date' => '1976-09-20',
            'is_published' => 0,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 4',
            'roles' => 'Team Lead',
            'client' => 'This is client 4',
            'description' => 'This is description 4',
            'content' => $content,
            'start_date' => '1976-08-20',
            'end_date' => '1976-10-20',
            'is_published' => 0,
        ]);

        $this->projects = collect($this->projects);
    }

    /**
     * @group get
     * @group rest
     * ? https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422#:~:text=The%20HyperText%20Transfer%20Protocol%20(HTTP,to%20process%20the%20contained%20instructions.
     */
    public function test_get_request_return_unprocessable_entity_422_response_because_project_parameters_not_valid() : void
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

    /**
     * @dataProvider parameterValueProvider
     * @group get
     * @group rest
     */
    public function test_get_request_returns_expected_result_default_parameters_rejected($pageValue, $perPageValue) : void
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
            'statusCode' => 422,
        ];

        $this->assertEquals($expectedResponse,$responseArray);
    }

    public function parameterValueProvider() : array
    {
        return [
            'float values' => [2.6, 22.2],
            'string values' => ['sam', 'fun'],
        ];
    }
}