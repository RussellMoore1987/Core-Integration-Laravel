<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Builder;

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

        $this->Project = Project::factory()->create([
            'title' => 'Really Cool Project!!!',
            'roles' => 'Software architect, UX UI designer',
            'client' => 'Creative Studio',
            'description' => "A really cool project.",
            'start_date' => '1976-05-20',
            'end_date' => '2021-05-20'
        ]);

        $this->projectQueryBuilderInstance = Project::query();

        $this->data = [
            'columnName' => 'start_date',
            'date' => '1976-05-20'
        ];

        $this->dateWhereClauseBuilder = new DateWhereClauseBuilder();
    }

    public function test_date_equals_clause()
    {
        $data = [
            'columnName' => 'start_date',
            'date' => '1976-05-20'
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $project = $builderInstance->first();

        $this->assertEquals($this->Project->id ,$project->id);
    }

    public function test_grater_than_clause()
    {
        $otherProject = Project::factory()->create([
            'title' => 'Really Cool Project!!!',
            'roles' => 'Software architect, UX UI designer',
            'client' => 'Creative Studio',
            'description' => "A really cool project.",
            'start_date' => '1989-05-20',
            'end_date' => '2021-05-20'
        ]);
        
        $data = [
            'columnName' => 'start_date',
            'date' => '1976-05-20::GT'
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $projects = $builderInstance->get();
        $project = $projects->find($otherProject->id);

        // $this->assertGreaterThan(2, $projects->count());
        $this->assertEquals(1 , $projects->count());
        $this->assertEquals($otherProject->id ,$project->id);
    }

    public function test_grater_than_or_equal_to_clause()
    {
        $otherProject = Project::factory()->create([
            'title' => 'Really Cool Project!!!',
            'roles' => 'Software architect, UX UI designer',
            'client' => 'Creative Studio',
            'description' => "A really cool project.",
            'start_date' => '1989-05-20',
            'end_date' => '2021-05-20'
        ]);
        
        $data = [
            'columnName' => 'start_date',
            'date' => '1976-05-20::GTE'
        ];
        
        $builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $data);
        
        $projects = $builderInstance->get();
        $project1 = $projects->find($otherProject->id);
        $project2 = $projects->find($this->Project->id);

        $this->assertGreaterThan(1, $projects->count());
        $this->assertEquals($otherProject->id ,$project1->id);
        $this->assertEquals($this->Project->id ,$project2->id);
    }
}
