<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FullRestApiTest extends TestCase
{
    use DatabaseTransactions;

    private $projects = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeProjects();
    }

    public function test_that_I_get_back_what_I_would_exsect_from_this_endpoint()
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
                'availableEndpointParameters',
                'availableEndpointParameters.parameters',
                'availableEndpointParameters.info',
                'rejectedParameters',
                'acceptedParameters',
                'endpointData'
            )->has('endpointData', fn ($json) =>
                $json->missing('class')->etc()
            )
        );

        // test that parameters are set right
        $parameters = 'availableEndpointParameters.parameters.';
        $response->assertJsonPath($parameters . 'id', 'int');
        $response->assertJsonPath($parameters . 'title', 'string');
        $response->assertJsonPath($parameters . 'start_date', 'date');
        $response->assertJsonPath($parameters . 'content', 'json');
        // ! start here *******************************************
        // TODO: add float and others if there are any
        
        // test that endpoint data is correct
        $response->assertJsonPath('endpointData.endpoint', 'projects');
        $response->assertJsonPath('endpointData.endpointId', $projectIds);
        $response->assertJsonPath('endpointData.endpointError', false);
        $response->assertJsonPath('endpointData.indexUrl', 'http://localhost:8000/api/v1/');
        $response->assertJsonPath('endpointData.url', 'http://localhost:8000/api/v1/projects/' . $projectIds);
        $response->assertJsonPath('endpointData.httpMethod', 'GET');
        $response->assertJsonPath('endpointData.endpointIdConvertedTo', ['id' => $projectIds]);

        // test page and per_page work
        $response->assertJsonPath('per_page', 1);
        $response->assertJsonPath('current_page', 2);

        // data count we expect back
        $response_array = json_decode($response->content(), true);
        $this->assertTrue(count($response_array['data']) <= 4);
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

    // Test
    // request with data I would expect 
    // request with one record 
    // 404 
    // empty data set 
    // ids - the many ways
    // GET with out authentication 
    // GET with authentication 
    // PUT, POST, PAtCH with authentication 
    // PUT, POST, PAtCH with out authentication, must fail 
    // All data types tested, and all there veronese
        // string
        // date
        // int
        // float
        // json
    // All extra parameters
        // method call
        // includes
        // page
        // perPage
    // Not caring about casing, columns, parameters
    // column data
    // form data
}