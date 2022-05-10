<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// TODO: Test additional endpoint with id like post_id with id parameter
// list of ids
// set up testing database
// post
// put
// patch
// delete

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
    public function test_get_back_ints_with_equal_to()
    {
        $projectId = $this->projects[0]->id;
        $response = $this->get("/api/v1/projects/$projectId?per_page=1");

        $response->assertStatus(200);
        $response->assertJson([
            'title' => 'Test Project 1',
            'is_published' => 1,
        ]);;
    }

    /**
     * @group db
     * @return void
     */
    public function test_get_back_ints_with_between_two_ints()
    {
        $response = $this->get("/api/v1/projects/?per_page=5&is_published=3,6::bt");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }

    /**
     * @dataProvider betweenOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_ints_with_between_two_ints_only_one_record_returned($option)
    {
        // only an ID passed in should return just one record without extra details around it
        $response = $this->get("/api/v1/projects/?per_page=5&is_published=4,6::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, count($res_array['data']));
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }

    public function betweenOptionDataProvider()
    {
        return [
            'bt' => ['bt'],
            'between' => ['between'],
        ];
    }

    /**
     * @dataProvider graterThenOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_ints_with_grater_than($option)
    {
        $response = $this->get("/api/v1/projects/?is_published=2::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }

    public function graterThenOptionDataProvider()
    {
        return [
            'gt' => ['gt'],
            'graterThen' => ['greaterThan'],
            '> grater then' => ['>'],
        ];
    }

    /**
     * @dataProvider greaterThanOrEqualOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_ints_with_grater_than_or_equal_option($option)
    {
        $response = $this->get("/api/v1/projects/?is_published=2::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
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
     * @dataProvider lessThanOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_ints_with_less_than_option($option)
    {
        $response = $this->get("/api/v1/projects/?is_published=3::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 1)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
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
     * @dataProvider lessThanOrEqualOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_ints_with_less_than_or_equal_option($option)
    {
        $response = $this->get("/api/v1/projects/?is_published=3::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 1)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
    }

    public function lessThanOrEqualOptionDataProvider()
    {
        return [
            'lte' => ['LTE'],
            'lessThanOrEqual' => ['lessThanOrEqual'],
            '<= less than or equal' => ['<='],
        ];
    }

    /**
     * @dataProvider inOptionDataProvider
     * @group db
     * @return void
     */
    public function test_get_back_ints_with_in_option($option)
    {
        $response = $this->get("/api/v1/projects/?is_published=1,2,3::{$option}");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 1)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
    }

    public function inOptionDataProvider()
    {
        return [
            'in' => ['in'],
            'in by default' => [''],
        ];
    }

    /**
     * @group db
     * @return void
     */
    public function test_get_back_int_with_not_in_option()
    {
        $response = $this->get("/api/v1/projects/?is_published=1,2,3::notIn");
        $res_array = json_decode($response->content(), true);
        
        $projects = collect($res_array['data']);

        $response->assertStatus(200);
        $this->assertEquals(1, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
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