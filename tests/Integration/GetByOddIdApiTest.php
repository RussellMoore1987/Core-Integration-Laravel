<?php

namespace Tests\Integration;

use App\Models\WorkHistoryType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// id are treated as integers but are utilized differently in the api, so they are tested separately

class GetByOddIdApiTest extends TestCase
{
    use DatabaseTransactions;

    private WorkHistoryType $workHistoryType1;
    private WorkHistoryType $workHistoryType2;
    private WorkHistoryType $workHistoryType3;
    private WorkHistoryType $workHistoryType4;

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_record_with_a_not_normal_id_in_pretty_url(): void
    {
        $this->createWorkHistoryTypes();

        $response = $this->get("/api/v1/workHistoryTypes/{$this->workHistoryType1->work_history_type_id}");

        $response->assertStatus(200);
        $response->assertJson([
            'work_history_type_id' => $this->workHistoryType1->work_history_type_id,
            'name' => $this->workHistoryType1->name,
            'icon' => $this->workHistoryType1->icon,
        ]);
    }

    /**
     * @group db
     * @group get
     * @group rest
     */
    public function test_get_back_record_with_a_not_normal_id_set_by_normal_id_parameter(): void
    {
        $this->createWorkHistoryTypes();
        $response = $this->get("/api/v1/workHistoryTypes/?id={$this->workHistoryType1->work_history_type_id}");

        $response->assertStatus(200);
        $response->assertJson([
            'work_history_type_id' => $this->workHistoryType1->work_history_type_id,
            'name' => $this->workHistoryType1->name,
            'icon' => $this->workHistoryType1->icon,
        ]);
    }

    /**
     * @dataProvider inOptionNotNormalIdProvider
     * @group db
     * @group get
     * @group rest
     * * just a sample test for odd id's, not all options are tested
     */
    public function test_get_back_records_with_the_in_option_with_a_not_normal_id(string $type, string $option): void
    {
        $this->createWorkHistoryTypes();
        
        $workHistoryTypesIds = [
            $this->workHistoryType1->work_history_type_id,
            $this->workHistoryType3->work_history_type_id,
        ];
        $workHistoryTypesIds = implode(',', $workHistoryTypesIds);
        $response = $this->get("/api/v1/workHistoryTypes/{$type}{$workHistoryTypesIds}{$option}");
        $responseArray = json_decode($response->content(), true);
        $workHistoryTypes = collect($responseArray['data']);
        
        $response->assertStatus(200);
        $this->assertEquals(2, $workHistoryTypes->count());
        $this->assertTrue((boolean) $workHistoryTypes->where(
            'work_history_type_id',
            $this->workHistoryType1->work_history_type_id
        )->first());
        $this->assertTrue((boolean) $workHistoryTypes->where(
            'work_history_type_id',
            $this->workHistoryType3->work_history_type_id
        )->first());
    }

    public function inOptionNotNormalIdProvider(): array
    {
        return [
            'in_id_pram' => ['?id=', '::in'],
            'in_by_default_id_pram' => ['?id=', ''],
            'in_pretty_url' => ['', '::in'],
            'in_by_default_pretty_url' => ['', ''],
        ];
    }

    private function createWorkHistoryTypes(): void
    {
        // WorkHistoryType->primaryKey = 'work_history_type_id', in the model
        // odd id name is work_history_type_id
        $this->workHistoryType1 = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 1',
                'icon' => 'Test Icon 1',
            ]
        );
        $this->workHistoryType2 = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 2',
                'icon' => 'Test Icon 2',
            ]
        );
        $this->workHistoryType3 = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 3',
                'icon' => 'Test Icon 3',
            ]
        );
        $this->workHistoryType4 = WorkHistoryType::factory()->create(
            [
                'name' => 'Test Work History Type 4',
                'icon' => 'Test Icon 4',
            ]
        );
    }
}
