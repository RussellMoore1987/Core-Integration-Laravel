<?php

namespace Tests\Integration;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// TODO: test 'equal', 'e', '='

class GetIntIntegrationApiTest extends TestCase
{
    use DatabaseTransactions;

    protected $projects;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeProjects();
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_ints_with_between_two_ints(): void
    {
        $response = $this->get("/api/v1/projects/?per_page=5&is_published=3,6::bt");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray);

        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }

    /**
     * @dataProvider betweenOptionDataProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_ints_with_between_two_ints_only_one_record_returned($option): void
    {
        // only an ID passed in should return just one record without extra details around it
        $response = $this->get("/api/v1/projects/?per_page=5&is_published=4,6::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray);

        $response->assertStatus(200);
        $this->assertEquals(1, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }

    public function betweenOptionDataProvider(): array
    {
        return [
            'bt' => ['bt'],
            'between' => ['between'],
        ];
    }

    /**
     * @dataProvider graterThenOptionDataProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_ints_with_grater_than($option): void
    {
        $response = $this->get("/api/v1/projects/?is_published=2::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray);

        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }

    public function graterThenOptionDataProvider(): array
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
     * @group get
     * @group rest
     */
    public function test_get_back_ints_with_grater_than_or_equal_option($option): void
    {
        $response = $this->get("/api/v1/projects/?is_published=2::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray);

        $response->assertStatus(200);
        $this->assertEquals(3, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }

    public function greaterThanOrEqualOptionDataProvider(): array
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
     * @group get
     * @group rest
     */
    public function test_get_back_ints_with_less_than_option($option): void
    {
        $response = $this->get("/api/v1/projects/?is_published=3::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray);

        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 1)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
    }

    public function lessThanOptionDataProvider(): array
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
     * @group get
     * @group rest
     */
    public function test_get_back_ints_with_less_than_or_equal_option($option): void
    {
        $response = $this->get("/api/v1/projects/?is_published=3::{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray);

        $response->assertStatus(200);
        $this->assertEquals(3, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 1)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
    }

    public function lessThanOrEqualOptionDataProvider(): array
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
     * @group get
     * @group rest
     */
    public function test_get_back_ints_with_in_option($option): void
    {
        $response = $this->get("/api/v1/projects/?is_published=1,2,3{$option}");
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray);

        $response->assertStatus(200);
        $this->assertEquals(3, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 1)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 3)->first());
    }

    public function inOptionDataProvider(): array
    {
        return [
            'in' => ['::in'],
            'in by default' => [''],
        ];
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_int_with_not_in_option(): void
    {
        $response = $this->get("/api/v1/projects/?IS_publIShed=1,2,3::notIn"); // IS_publIShed, shows case insensitivity
        $responseArray = json_decode($response->content(), true);
        
        $projects = collect($responseArray);

        $response->assertStatus(200);
        $this->assertEquals(1, $projects->count());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }
        
    protected function makeProjects(): void
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
}