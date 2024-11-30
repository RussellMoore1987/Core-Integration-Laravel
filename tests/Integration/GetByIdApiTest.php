<?php

namespace Tests\Integration;

use App\Models\Project;
use App\Models\WorkHistoryType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// id are treated as integers but are utilized differently in the api, so they are tested separately

class GetByIdApiTest extends TestCase
{
    use DatabaseTransactions;

    private Project $project1;
    private Project $project2;
    private Project $project3;
    private Project $project4;

    private WorkHistoryType $workHistoryType1;
    private WorkHistoryType $workHistoryType2;
    private WorkHistoryType $workHistoryType3;
    private WorkHistoryType $workHistoryType4;

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
     * @dataProvider betweenOptionDataProvider
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

    public static function betweenOptionDataProvider(): array
    {
        return [
            'bt_id_pram' => ['?id=', 'bt'],
            'between_id_pram' => ['?id=', 'between'],
            'bt_pretty_url' => ['', 'bt'],
            'between_pretty_url' => ['', 'between'],
        ];
    }

    /**
     * @dataProvider graterThenOptionDataProvider
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

    public function graterThenOptionDataProvider(): array
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
     * @dataProvider greaterThanOrEqualOptionDataProvider
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
    
    public function greaterThanOrEqualOptionDataProvider(): array
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
     * @dataProvider lessThanOptionDataProvider
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

    public function lessThanOptionDataProvider(): array
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
     * @dataProvider lessThanOrEqualOptionDataProvider
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

    public function lessThanOrEqualOptionDataProvider(): array
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
     * @dataProvider inOptionDataProvider
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

    public function inOptionDataProvider(): array
    {
        return [
            'in_id_pram' => ['?id=', '::in'],
            'in_by_default_id_pram' => ['?id=', ''],
            'in_pretty_url' => ['', '::in'],
            'in_by_default_pretty_url' => ['', ''],
        ];
    }

    /**
     * @dataProvider notInOptionDataProvider
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

    public function notInOptionDataProvider(): array
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

    // ================================================================================================================
    // odd id's start here
    // ================================================================================================================

    // TODO: do odd id samples
    // ! start here ***************************************************

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_record_with_not_normal_id_in_pretty_url(): void
    {
        // WorkHistoryType->primaryKey = 'work_history_type_id', in the model
        $workHistoryType = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 1',
                'icon' => 'Test Icon 1',
            ]
        );
        $this->createWorkHistoryTypes();

        $response = $this->get("/api/v1/workHistoryTypes/{$workHistoryType->work_history_type_id}");

        $response->assertStatus(200);
        $response->assertJson([
            'work_history_type_id' => $workHistoryType->work_history_type_id,
            'name' => $workHistoryType->name,
            'icon' => $workHistoryType->icon,
        ]);
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_record_with_not_normal_id_set_by_normal_id_parameter(): void
    {
        
        $workHistoryType = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 1',
                'icon' => 'Test Icon 1',
            ]
        );
        $response = $this->get("/api/v1/workHistoryTypes/?id={$workHistoryType->work_history_type_id}");

        $response->assertStatus(200);
        $response->assertJson([
            'work_history_type_id' => $workHistoryType->work_history_type_id,
            'name' => $workHistoryType->name,
            'icon' => $workHistoryType->icon,
        ]);
    }

    private function createWorkHistoryTypes(): void
    {
        // WorkHistoryType->primaryKey = 'work_history_type_id', in the model
        // odd id name is work_history_type_id
        $this->$workHistoryType1 = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 1',
                'icon' => 'Test Icon 1',
            ]
        );
        $this->$workHistoryType2 = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 2',
                'icon' => 'Test Icon 2',
            ]
        );
        $this->$workHistoryType3 = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 3',
                'icon' => 'Test Icon 3',
            ]
        );
        $this->$workHistoryType4 = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 4',
                'icon' => 'Test Icon 4',
            ]
        );
    }
}
