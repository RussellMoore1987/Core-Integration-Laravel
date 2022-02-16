<?php

namespace Tests\Unit\ClauseBuilder;

use App\CoreIntegrationApi\CIL\ClauseBuilder\IntWhereClauseBuilder;
use App\Models\Project;
use Tests\TestCase;

class IntWhereClauseBuilderTest extends TestCase
{
    private $queryBuilder;
    private $intWhereClauseBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBuilder = Project::query();

        $this->intWhereClauseBuilder = new IntWhereClauseBuilder();
    }

    /**
     * @test
     *
     * @covers ::build
     * @dataProvider dataSetup
     */
    public function test_it_builds_the_correct_query($data, $expectedClause, $expectedBindings)
    {
        $resultQueryBuilder = $this->intWhereClauseBuilder->build($this->queryBuilder, $data);

        // dd($resultQueryBuilder->toSql(), $resultQueryBuilder->getBindings());

        $this->assertStringContainsString($expectedClause, $resultQueryBuilder->toSql());
        $this->assertEquals($expectedBindings, $resultQueryBuilder->getBindings());
    }

    // ! working here **********************************************************
    public function dataSetup()
    {
        return [
            'It returns an = SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '=',
                ],
                'expectedClause' => 'where `team_lead_id` = ?',
                'expectedBindings' => [33]
            ],
            'It returns an > SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '>',
                ],
                'expectedClause' => 'where `team_lead_id` > ?',
                'expectedBindings' => [33]
            ],
            'It returns an >= SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '>=',
                ],
                'expectedClause' => 'where `team_lead_id` >= ?',
                'expectedBindings' => [33]
            ],
            'It returns an < SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '<',
                ],
                'expectedClause' => 'where `team_lead_id` < ?',
                'expectedBindings' => [33]
            ],
            'It returns an <= SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '<=',
                ],
                'expectedClause' => 'where `team_lead_id` <= ?',
                'expectedBindings' => [33]
            ],
        ];
    }
}
