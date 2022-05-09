<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// TODO: Test additional endpoint with id like post_id with id parameter

class IntEndToEndApiTest extends TestCase
{
    use DatabaseTransactions;

    private $projects = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeProjects();
    }

    /**
     * @group db
     * @return void
     */
    public function test_get_back_ints_equal_to()
    {
        $projectId = $this->projects[0]->id;
        $response = $this->get("/api/v1/projects/$projectId?per_page=1");

        $response->assertStatus(200);
        $response->assertJson([
            'title' => 'Test Project 1',
            'is_published' => 1,
        ]);;
    }

    public function test_get_back_ints_equal_between_two_ints()
    {
        $response = $this->get("/api/v1/projects/?per_page=1&is_published=4,6::bt");
        $res_array = json_decode($response->content(), true);

        $response->assertStatus(200);
        $this->assertEquals(1, count($res_array['data']));
        $this->assertEquals('Test Project 4', $res_array['data'][0]['title']);
        $this->assertEquals(4, $res_array['data'][0]['is_published']);
    }
        

    protected function makeProjects()
    {
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 1',
            'is_published' => 1,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 2',
            'is_published' => 2,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 3',
            'is_published' => 3,
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 4',
            'is_published' => 4,
        ]);

        $this->projects = collect($this->projects);
    }

    // TODO: Test
    // GET with out authentication 
    // GET with authentication 
    // PUT, POST, PAtCH with authentication 
    // PUT, POST, PAtCH with out authentication, must fail 
    // PUT, POST, PAtCH response
    // PUT response code
    // POST response code
    // PAtCH response code
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