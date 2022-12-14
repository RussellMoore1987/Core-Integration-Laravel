<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

// TODO: Test additional endpoint with id like post_id with id parameter

class FullRestApiTest extends TestCase
{
    use DatabaseTransactions;

    private $projects;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeProjects();
    }

    /**
     * @group db
     * @return void
     */
    public function test_that_what_I_get_back_is_what_I_would_exsect_from_this_endpoint_testing_json_structure()
    {
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
     * @group db
     */
    public function test_return_of_one_record() : void
    {
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
     * @group db
     */
    public function test_performance() : void
    {
        $time_start = microtime(true);
        for ($i=0; $i < 1000; $i++) { 
            $response = $this->get('/api/v1/projects?start_date=2021-03-09::LT&is_published=1');
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start); // seconds
        dd("Total Execution Time test_performance: {$execution_time} seconds");
    }
    // by reference 1000 times
        // 7.4322929382324 
        // 6.5321760177612
        // 7.3061909675598
        // 6.5125818252563
        // 6.6576659679413


    /**
     * @group db
     */
    public function test_return_404_response() : void
    {
        $response = $this->get('/api/v1/projects/9999999999');

        $response->assertStatus(404);
        $response->assertJsonPath('message', 'The record with the id of 9999999999 at the "projects" endpoint was not found');
    }

    /**
     * @group db
     */
    public function test_return_of_empty_data_set() : void
    {
        $response = $this->get('/api/v1/projects/?start_date=1000-02-01');

        $response->assertStatus(200);
        $response_array = json_decode($response->content(), true);
        $this->assertTrue(count($response_array['data']) == 0);
    }

    /**
     * @group db
     * @return void
     */
    public function test_return_of_column_data()
    {
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

    protected function makeProjects()
    {
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 1',
            'roles' => 'Developer',
            'client' => 'This is client 1',
            'description' => 'This is description 1',
            'content' => '{"error":"error", "big":"big", "name":"Sam", "array":{"color":"red"}}',
            'start_date' => '1976-05-20',
            'end_date' => '1976-07-20',
            'is_published' => 1,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 2',
            'roles' => 'UX UI Designer',
            'client' => 'This is client 2',
            'description' => 'This is description 2',
            'content' => '{"error":"error", "big":"big", "name":"Sam", "array":{"color":"red"}}',
            'start_date' => '1976-06-20',
            'end_date' => '1976-08-20',
            'is_published' => 1,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 3',
            'roles' => 'Designer',
            'client' => 'This is client 3',
            'description' => 'This is description 3',
            'content' => '{"error":"error", "big":"big", "name":"Sam", "array":{"color":"red"}}',
            'start_date' => '1976-07-20',
            'end_date' => '1976-09-20',
            'is_published' => 0,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 4',
            'roles' => 'Team Lead',
            'client' => 'This is client 4',
            'description' => 'This is description 4',
            'content' => '{"error":"error", "big":"big", "name":"Sam", "array":{"color":"red"}}',
            'start_date' => '1976-08-20',
            'end_date' => '1976-10-20',
            'is_published' => 0,
        ]);

        $this->projects = collect($this->projects);
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