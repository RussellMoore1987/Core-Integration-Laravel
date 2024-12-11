<?php

namespace Tests\Integration;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// id are treated as integers but are utilized differently in the api, so they are tested separately
// ! start here ************************************************************** after this look at other starting point in GetDefaultParamsTest.php
// TODO: test this
// {
//     "message": "The record with the id of 200 at the \"projects\" endpoint was not found" // just id
// }
// to
// {
//     "message": "The record with the id of 200 and the criteria provided for the \"projects\" endpoint yielded no results" // id and criteria
// maybe add accepted parameters to the response
// }

class GetByIdApiTest extends TestCase
{
    use DatabaseTransactions;

    private Project $project1;
    private Project $project2;
    private Project $project3;
    private Project $project4;

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_id_in_pretty_url(): void
    {
        $this->createProjects();

        $projectId = $this->project1->id;
        $response = $this->get("/api/v1/projects/$projectId");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $projectId,
            'title' => $this->project1->title,
            'is_published' => $this->project1->is_published,
        ]);
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_list_of_ids_in_url(): void
    {
        $this->createProjects();
        
        $projectIds = [
            $this->project1->id,
            $this->project3->id,
        ];
        $projectIds = implode(',', $projectIds);
        $response = $this->get("/api/v1/projects/{$projectIds}");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);
        
        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('id', $this->project1->id)->first());
        $this->assertTrue((boolean) $projects->where('id', $this->project3->id)->first());
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_correct_404_status_no_extra_prams(): void
    {
        $this->createProjects();
        
        $response = $this->get("/api/v1/projects/200");
        
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'The record with the id of 200 at the "projects" endpoint was not found',
        ]);
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_correct_404_status_extra_prams(): void
    {
        $this->createProjects();
        
        $response = $this->get("/api/v1/projects/200?is_published=12");
        
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'The record with the id of 200 and the criteria provided for the "projects" endpoint yielded no results',
            'acceptedParameters' => [
                'endpoint' => [
                    'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost:8000/api/v1/',
                ],
                'id' => [
                    'intConvertedTo' => 200,
                    'originalIntString' => '200',
                    'comparisonOperatorConvertedTo' => '=',
                    'originalComparisonOperator' => null,
                ],
                'is_published' => [
                    'intConvertedTo' => 12,
                    'originalIntString' => '12',
                    'comparisonOperatorConvertedTo' => '=',
                    'originalComparisonOperator' => null,
                ],
            ],
            'ignoredParameters' => [],
        ]);
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_correct_404_status_extra_prams_page_per_page(): void
    {
        $this->createProjects();
        
        $response = $this->get("/api/v1/projects/200?is_published=12&page=2&perPage=0");
        
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'The record with the id of 200 and the criteria provided for the "projects" endpoint yielded no results',
            'acceptedParameters' => [
                'endpoint' => [
                    'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost:8000/api/v1/',
                ],
                'id' => [
                    'intConvertedTo' => 200,
                    'originalIntString' => '200',
                    'comparisonOperatorConvertedTo' => '=',
                    'originalComparisonOperator' => null,
                ],
                'is_published' => [
                    'intConvertedTo' => 12,
                    'originalIntString' => '12',
                    'comparisonOperatorConvertedTo' => '=',
                    'originalComparisonOperator' => null,
                ],
            ],
            'ignoredParameters' => [
                'page' => 2,
                'perPage' => 0,
            ],
        ]);
    }

    /**
     * @dataProvider betweenOptionProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_between(string $type, string $option): void
    {
        $this->createProjects();
        
        // only an ID passed in should return just one record without extra details around it
        $project_ids = $this->project4->id . ',' . ($this->project4->id + 10);
        $response = $this->get("/api/v1/projects/{$type}{$project_ids}::{$option}");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);
        
        $response->assertStatus(200);
        $this->assertEquals(1, $projects->count());
        $this->assertTrue((boolean) $projects->where('id', $this->project4->id)->first());
    }

    public static function betweenOptionProvider(): array
    {
        return [
            'bt_id_pram' => ['?id=', 'bt'],
            'between_id_pram' => ['?id=', 'between'],
            'bt_pretty_url' => ['', 'bt'],
            'between_pretty_url' => ['', 'between'],
        ];
    }

    /**
     * @dataProvider graterThenOptionProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_grater_than(string $type, string $option): void
    {
        $this->createProjects();
        
        $project_id = $this->project3->id;
        $response = $this->get("/api/v1/projects/{$type}{$project_id}::{$option}");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);
        
        $response->assertStatus(200);
        $this->assertEquals(1, $projects->count());
        $this->assertTrue((boolean) $projects->where('id', $this->project4->id)->first());
    }

    public function graterThenOptionProvider(): array
    {
        return [
            'gt_id_pram' => ['?id=', 'gt'],
            'greaterThan_id_pram' => ['?id=', 'greaterThan'],
            '>_grater_then_id_pram' => ['?id=', '>'],
            'gt_pretty_url' => ['', 'gt'],
            'graterThen_pretty_url' => ['', 'greaterThan'],
            '>_grater_then_pretty_url' => ['', '>'],
        ];
    }

    /**
     * @dataProvider greaterThanOrEqualOptionProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_grater_than_or_equal_to_option(string $type, string $option): void
    {
        $this->createProjects();
        
        $project_id = $this->project3->id;
        $response = $this->get("/api/v1/projects/{$type}{$project_id}::{$option}");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);
        
        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('id', $this->project3->id)->first());
        $this->assertTrue((boolean) $projects->where('id', $this->project4->id)->first());
    }
    
    public function greaterThanOrEqualOptionProvider(): array
    {
        return [
            'gte_id_pram' => ['?id=', 'gte'],
            'greaterThanOrEqual_id_pram' => ['?id=', 'greaterThanOrEqual'],
            '>=_greater_than_or_equal_id_pram' => ['?id=', '>='],
            'gte_pretty_url' => ['', 'GTE'],
            'greaterThanOrEqual_pretty_url' => ['', 'GreaterThanOrEqual'],
            '>=_greater_than_or_equal_pretty_url' => ['', '>='],
        ];
    }

    /**
     * @dataProvider lessThanOptionProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_less_than_option(string $type, string $option): void
    {
        $this->createProjects();
        
        $projectId = $this->project3->id;
        $response = $this->get("/api/v1/projects/{$type}{$projectId}::{$option}");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('id', $this->project1->id)->first());
        $this->assertTrue((boolean) $projects->where('id', $this->project2->id)->first());
    }

    public function lessThanOptionProvider(): array
    {
        return [
            'lte_id_pram' => ['?id=', 'lt'],
            'lessThanOrEqual_id_pram' => ['?id=', 'lessThan'],
            '<_less_than_id_pram' => ['?id=', '<'],
            'lte_pretty_url' => ['', 'lt'],
            'lessThanOrEqual_pretty_url' => ['', 'lessThan'],
            '<_less_than_pretty_url' => ['', '<'],
        ];
    }

    /**
     * @dataProvider lessThanOrEqualOptionProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_less_than_or_equal_option(string $type, string $option): void
    {
        $this->createProjects();
        
        $projectId = $this->project3->id;
        $response = $this->get("/api/v1/projects/{$type}{$projectId}::{$option}");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(3, $projects->count());
        $this->assertTrue((boolean) $projects->where('id', $this->project1->id)->first());
        $this->assertTrue((boolean) $projects->where('id', $this->project2->id)->first());
        $this->assertTrue((boolean) $projects->where('id', $this->project3->id)->first());
    }

    public function lessThanOrEqualOptionProvider(): array
    {
        return [
            'lte_id_pram' => ['?id=', 'LTE'],
            'lessThanOrEqual_id_pram' => ['?id=', 'lessThanOrEqual'],
            '<=_less_than_or_equal_id_pram' => ['?id=', '<='],
            'lte_pretty_url' => ['', 'LTE'],
            'lessThanOrEqual_pretty_url' => ['', 'lessThanOrEqual'],
            '<=_less_than_or_equal_pretty_url' => ['', '<='],
        ];
    }
    
    /**
     * @dataProvider inOptionProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_the_in_option(string $type, string $option): void
    {
        $this->createProjects();
        
        $projectIds = [
            $this->project1->id,
            $this->project3->id,
        ];
        $projectIds = implode(',', $projectIds);
        $response = $this->get("/api/v1/projects/{$type}{$projectIds}{$option}");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);
        
        $response->assertStatus(200);
        $this->assertEquals(2, $projects->count());
        $this->assertTrue((boolean) $projects->where('id', $this->project1->id)->first());
        $this->assertTrue((boolean) $projects->where('id', $this->project3->id)->first());
    }

    public function inOptionProvider(): array
    {
        return [
            'in_id_pram' => ['?id=', '::in'],
            'in_by_default_id_pram' => ['?id=', ''],
            'in_pretty_url' => ['', '::in'],
            'in_by_default_pretty_url' => ['', ''],
        ];
    }

    /**
     * @dataProvider notInOptionProvider
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_records_with_list_of_ids_not_in_option(string $type): void
    {
        $this->createProjects();
        
        $projectIds = [
            $this->project1->id,
            $this->project3->id,
        ];
        $projectIds = implode(',', $projectIds);
        $response = $this->get("/api/v1/projects/{$type}{$projectIds}::notIn");
        $responseArray = json_decode($response->content(), true);
        $projects = collect($responseArray['data']);

        $response->assertStatus(200);
        $this->assertEquals(2, count($responseArray['data']));
        $this->assertTrue((boolean) $projects->where('is_published', 2)->first());
        $this->assertTrue((boolean) $projects->where('is_published', 4)->first());
    }

    public function notInOptionProvider(): array
    {
        return [
            'notIn_id_pram' => ['?id='],
            'notIn_pretty_url' => [''],
        ];
    }
    
    private function createProjects(): void
    {
        $this->project1 = Project::factory()->create([
            'title' => 'Test Project 1',
            'is_published' => 1,
        ]);
        $this->project2 = Project::factory()->create([
            'title' => 'Test Project 2',
            'is_published' => 2,
        ]);
        $this->project3 = Project::factory()->create([
            'title' => 'Test Project 3',
            'is_published' => 3,
        ]);
        $this->project4 = Project::factory()->create([
            'title' => 'Test Project 4',
            'is_published' => 4,
        ]);
    }
}
