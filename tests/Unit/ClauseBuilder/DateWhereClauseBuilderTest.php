<?php

namespace Tests\Unit\ClauseBuilder;

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

        $this->projectQueryBuilderInstance = Project::query();

        $this->data = [
            'columnName' => 'start_date',
            'date' => '1976-05-20'
        ];

        $this->dateWhereClauseBuilder = new DateWhereClauseBuilder();

        $this->builderInstance = $this->dateWhereClauseBuilder->build($this->projectQueryBuilderInstance, $this->data);
    }

    public function test_date_clause_builder_to_see_if_it_returns_an_instance_of_the_laravel_builder_class()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $this->builderInstance);
    }

    public function test_to_make_sure_builder_has_what_we_expect()
    {
        $builderInstanceWheres = $this->builderInstance->getQuery()->wheres[0];
        $builderInstanceFrom = $this->builderInstance->getQuery()->from;

        $this->assertEquals('start_date' ,$builderInstanceWheres['column']);
        $this->assertEquals('1976-05-20' ,$builderInstanceWheres['value']);
        $this->assertEquals('=' ,$builderInstanceWheres['operator']);
        $this->assertEquals('Date' ,$builderInstanceWheres['type']);

        $this->assertEquals('projects' ,$builderInstanceFrom);
    }

    public function test_to_make_sure_we_can_get_back_a_record_from_the_builder_that_is_returned()
    {
        $createdProject = Project::factory()->create([
            'title' => 'Really Cool Project!!!',
            'roles' => 'Software architect, UX UI designer',
            'client' => 'Creative Studio',
            'description' => "A really cool project.",
            'start_date' => '1976-05-20',
            'end_date' => '2021-05-20'
        ]);

        $project = $this->builderInstance->first();

        $this->assertEquals($createdProject->id ,$project->id);
    }
}
