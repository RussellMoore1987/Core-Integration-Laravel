<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// TODO:
// post
// put
// patch
// delete

class DateEndToEndApiTest extends TestCase
{
    use DatabaseTransactions;

    protected $projects = [];
    protected $project1Title = 'Test Project 1';
    protected $project2Title = 'Test Project 2';
    protected $project3Title = 'Test Project 3';
    protected $project4Title = 'Test Project 4';

    protected function setUp() : void
    {
        parent::setUp();

        $this->makeProjects();
    }

    // ==============================================================================
    // GET API Tests
    // ==============================================================================

    /**
     * @group db
     * @group rest
     * @group get
     */
    public function test_get_back_record_with_date_time_equal_to() : void
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:30:45");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project1Title)->first());
    }

    /**
     * @group db
     * @group rest
     * @group get
     */
    public function test_get_back_records_with_date_equal_to() : void
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project1Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project2Title)->first());
    }

    /**
     * @group db
     * @group rest
     * @group get
     */
    public function test_get_back_records_with_date_time_between() : void
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:45:56,2010-01-01::bt");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project3Title)->first());
    }

    /**
     * @dataProvider betweenOptionDataProvider
     * @group db
     * @group rest
     * @group get
     */
    public function test_get_back_records_with_date_between_options($option) : void
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01,2010-01-01::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project1Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project2Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project3Title)->first());
    }

    public function betweenOptionDataProvider() : array
    {
        return [
            'bt' => ['bt'],
            'between' => ['between'],
        ];
    }

    /**
     * @group db
     */
    public function test_get_back_record_with_date_time_grater_than() : void
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 11:23:33::gt");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project1Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project3Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project4Title)->first());
    }

    /**
     * @dataProvider graterThenOptionDataProvider
     * @group db
     */
    public function test_get_back_record_with_date_grater_than_options($option) : void
    {
        $response = $this->get("/api/v1/projects/?start_date=2010-01-01::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project4Title)->first());
    }

    public function graterThenOptionDataProvider() : array
    {
        return [
            'gt' => ['gt'],
            'graterThen' => ['greaterThan'],
            '> grater then' => ['>'],
        ];
    }

    /**
     * @group db
     */
    public function test_get_back_record_with_date_time_grater_than_or_equal() : void
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:30:45::GTE");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project1Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project3Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project4Title)->first());
    }

    /**
     * @dataProvider greaterThanOrEqualOptionDataProvider
     * @group db
     */
    public function test_get_back_records_with_date_grater_than_or_equal_options($option) : void
    {
        $response = $this->get("/api/v1/projects/?start_date=2010-01-01::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project3Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project4Title)->first());
    }

    public function greaterThanOrEqualOptionDataProvider() : array
    {
        return [
            'gte' => ['GTE'],
            'greaterThanOrEqual' => ['GreaterThanOrEqual'],
            '>= greater than or equal' => ['>='],
        ];
    }

    /**
     * @group db
     */
    public function test_get_back_records_with_date_time_less_than() : void
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:30:45::lt");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project2Title)->first());
    }

    /**
     * @dataProvider lessThanOptionDataProvider
     * @group db
     */
    public function test_get_back_records_with_date_less_than_options($option) : void
    {
        $response = $this->get("/api/v1/projects/?start_date=2010-01-01::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project1Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project2Title)->first());
    }

    public function lessThanOptionDataProvider() : array
    {
        return [
            'lte' => ['lt'],
            'lessThanOrEqual' => ['lessThan'],
            '< less than' => ['<'],
        ];
    }

    /**
     * @group db
     */
    public function test_get_back_records_with_date_less_than_or_equal() : void
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:30:45::lte");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project1Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project2Title)->first());
    }

    /**
     * @dataProvider lessThanOrEqualOptionDataProvider
     * @group db
     */
    public function test_get_back_records_with_date_less_than_or_equal_options($option) : void
    {
        $response = $this->get("/api/v1/projects/?start_date=2010-01-01::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('title', $this->project1Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project2Title)->first());
        $this->assertTrue((boolean) $projects->where('title', $this->project3Title)->first());
    }

    public function lessThanOrEqualOptionDataProvider() : array
    {
        return [
            'lte' => ['LTE'],
            'lessThanOrEqual' => ['lessThanOrEqual'],
            '<= less than or equal' => ['<='],
        ];
    }

    // ==============================================================================
    // POST API Tests
    // ==============================================================================

    /**
     * @group db
     */
    // TODO: should Ido this here???
    // public function test_post_record_with_valid_data() : void
    // {
    //     $response = $this->post("/api/v1/projects/", [
    //         'title' => 'Test Project 5',
    //         'description' => 'Test Project 5 description',
    //         'start_date' => '2010-01-01',
    //         'end_date' => '2010-01-01',
    //         'status' => 'active',
    //     ]);
    //
    //     $response->assertStatus(201);
    //     $this->assertDatabaseHas('projects', [
    //         'title' => 'Test Project 5',
    //         'description' => 'Test Project 5 description',
    //         'start_date' => '2010-01-01',
    //         'end_date' => '2010-01-01',
    //         'status' => 'active',
    //     ]);
    // }

        
    protected function makeProjects() : void
    {
        $this->projects[] = Project::factory()->create([
            'title' => $this->project1Title,
            'start_date' => '1979-01-01 12:30:45',
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => $this->project2Title,
            'start_date' => '1979-01-01',
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => $this->project3Title,
            'start_date' => '2010-01-01',
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => $this->project4Title,
            'start_date' => '2022-01-01',
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