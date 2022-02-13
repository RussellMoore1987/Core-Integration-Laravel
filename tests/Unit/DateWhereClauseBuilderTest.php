<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\CIL\ClauseBuilder\DateWhereClauseBuilder;
use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DateWhereClauseBuilderTest extends TestCase
{
    use DatabaseTransactions;

    private $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->project = Project::factory()->create([
            'start_date' => '1976-05-20',
        ]);

        $this->projectQueryBuilderInstance = Project::query();

        $this->dateWhereClauseBuilder = new DateWhereClauseBuilder();
    }

    // tests ------------------------------------------------------------
    public function test_date_equals_clause()
    {
        $data = [
            'columnName' => 'start_date',
            'comparisonOperator' => '=',
            'date' => '1976-05-20'
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $project = $builderInstance->first();

        $this->assertEquals($this->project->id ,$project->id);
    }

    public function test_grater_than_clause()
    {
        $otherProject = $this->makeOtherProject();
        
        $data = [
            'columnName' => 'start_date',
            'comparisonOperator' => '>',
            'date' => '1976-05-20'
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $projects = $builderInstance->get();

        $this->assertEquals(1 , $projects->count());
        $this->assertTrue((boolean)$projects->find($otherProject->id));
    }

    public function test_grater_than_or_equal_to_clause()
    {
        $otherProject = $this->makeOtherProject();
        
        $data = [
            'columnName' => 'start_date',
            'comparisonOperator' => '>=',
            'date' => '1976-05-20'
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $projects = $builderInstance->get();

        $this->assertGreaterThan(1, $projects->count());
        $this->assertTrue((boolean)$projects->find($otherProject->id));
        $this->assertTrue((boolean)$projects->find($this->project->id));
    }

    public function test_less_than_clause()
    {
        $data = [
            'columnName' => 'start_date',
            'comparisonOperator' => '<',
            'date' => '1989-05-20'
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $projects = $builderInstance->get();

        $this->assertEquals(1 , $projects->count());
        $this->assertTrue((boolean)$projects->find($this->project->id));
    }

    public function test_less_than_or_equal_to_clause()
    {
        $otherProject = $this->makeOtherProject();
        
        $data = [
            'columnName' => 'start_date',
            'comparisonOperator' => '<=',
            'date' => '1989-05-20'
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $projects = $builderInstance->get();

        $this->assertGreaterThan(1, $projects->count());
        $this->assertTrue((boolean)$projects->find($otherProject->id));
        $this->assertTrue((boolean)$projects->find($this->project->id));
    }

    public function test_between_clause()
    {
        $otherProject = $this->makeOtherProject();
        
        $data = [
            'columnName' => 'start_date',
            'comparisonOperator' => 'bt',
            'date' => ['1976-05-20','1989-05-20']
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $projects = $builderInstance->get();

        $this->assertGreaterThan(1, $projects->count());
        $this->assertTrue((boolean)$projects->find($otherProject->id));
        $this->assertTrue((boolean)$projects->find($this->project->id));
    }

    // helper functions -------------------------------------------------
    private function makeOtherProject()
    {
        return Project::factory()->create([
            'start_date' => '1989-05-20',
        ]);
    }
}
