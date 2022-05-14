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

    private $projects = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeProjects();
    }

    // ==============================================================================
    // GET API Tests
    // ==============================================================================

    /**
     * @group db
     * @return void
     */
    public function test_get_back_record_with_date_time_equal_to()
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:30:45");
        $res_array = json_decode($response->content(), true);
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 1')->first());
    }

    /**
     * @group db
     * @return void
     */
    public function test_get_back_records_with_date_equal_to()
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01");
        $res_array = json_decode($response->content(), true);
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 1')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 2')->first());
    }

    /**
     * @group db
     * @return void
     */
    public function test_get_back_records_with_date_time_between()
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:45:56,2010-01-01::bt"); 
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 3')->first());
    }

    /**
     * @dataProvider betweenOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_records_with_date_between_options($option)
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01,2010-01-01::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 1')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 2')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 3')->first());
    }

    public function betweenOptionDataProvider()
    {
        return [
            'bt' => ['bt'],
            'between' => ['between'],
        ];
    }

    /**
     * @group db
     * @return void
     */
    public function test_get_back_record_with_date_time_grater_than()
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 11:23:33::gt");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 1')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 3')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 4')->first());
    }

    /**
     * @dataProvider graterThenOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_record_with_date_grater_than_options($option)
    {
        $response = $this->get("/api/v1/projects/?start_date=2010-01-01::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 4')->first());
    }

    public function graterThenOptionDataProvider()
    {
        return [
            'gt' => ['gt'],
            'graterThen' => ['greaterThan'],
            '> grater then' => ['>'],
        ];
    }

    // 
    /**
     * @group db
     * @return void
     */
    public function test_get_back_record_with_date_time_grater_than_or_equal()
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:30:45::GTE");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 1')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 3')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 4')->first());
    }

    /**
     * @dataProvider greaterThanOrEqualOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_records_with_date_grater_than_or_equal_options($option)
    {
        $response = $this->get("/api/v1/projects/?start_date=2010-01-01::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 3')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 4')->first());
    }

    public function greaterThanOrEqualOptionDataProvider()
    {
        return [
            'gte' => ['GTE'],
            'greaterThanOrEqual' => ['GreaterThanOrEqual'],
            '>= greater than or equal' => ['>='],
        ];
    }

    /**
     * @group db
     * @return void
     */
    public function test_get_back_records_with_date_time_less_than()
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:30:45::lt");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 2')->first());
    }

    /**
     * @dataProvider lessThanOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_records_with_date_less_than_options($option)
    {
        $response = $this->get("/api/v1/projects/?start_date=2010-01-01::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 1')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 2')->first());
    }

    public function lessThanOptionDataProvider()
    {
        return [
            'lte' => ['lt'],
            'lessThanOrEqual' => ['lessThan'],
            '< less than' => ['<'],
        ];
    }

    /**
     * @group db
     * @return void
     */
    public function test_get_back_records_with_date_less_than_or_equal()
    {
        $response = $this->get("/api/v1/projects/?start_date=1979-01-01 12:30:45::lte");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 1')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 2')->first());
    }

    /**
     * @dataProvider lessThanOrEqualOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_records_with_date_less_than_or_equal_options($option)
    {
        $response = $this->get("/api/v1/projects/?start_date=2010-01-01::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 1')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 2')->first());
        $this->assertTrue((boolean) $projects->where('title', 'Test Project 3')->first());
    }

    public function lessThanOrEqualOptionDataProvider()
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
     * @return void
     */
    // TODO: should Ido this here???
    // public function test_post_record_with_valid_data() 
    // {
    //     $response = $this->post("/api/v1/projects/", [
    //         'title' => 'Test Project 5',
    //         'description' => 'Test Project 5 description',
    //         'start_date' => '2010-01-01',
    //         'end_date' => '2010-01-01',
    //         'status' => 'active',
    //     ]);

    //     $response->assertStatus(201);
    //     $this->assertDatabaseHas('projects', [
    //         'title' => 'Test Project 5',
    //         'description' => 'Test Project 5 description',
    //         'start_date' => '2010-01-01',
    //         'end_date' => '2010-01-01',
    //         'status' => 'active',
    //     ]);
    // }
        
    protected function makeProjects()
    {
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 1',
            'start_date' => '1979-01-01 12:30:45',
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 2',
            'start_date' => '1979-01-01',
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 3',
            'start_date' => '2010-01-01',
        ]);
        $this->projects[] = Project::factory()->create([
            'title' => 'Test Project 4',
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