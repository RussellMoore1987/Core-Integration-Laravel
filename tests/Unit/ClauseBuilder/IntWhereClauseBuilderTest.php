<?php

namespace Tests\Unit\ClauseBuilder;

use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\IntWhereClauseBuilder;
use App\Models\Project;
use Tests\TestCase;

class IntWhereClauseBuilderTest extends TestCase
{
    protected $queryBuilder;
    protected $intWhereClauseBuilder;

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

        $this->assertStringContainsString($expectedClause, $resultQueryBuilder->toSql());
        $this->assertEquals($expectedBindings, $resultQueryBuilder->getBindings());
    }

    public function dataSetup()
    {
        return [
            'It returns an "=" SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '=',
                ],
                'expectedClause' => 'where `team_lead_id` = ?',
                'expectedBindings' => [33],
            ],
            'It returns an ">" SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '>',
                ],
                'expectedClause' => 'where `team_lead_id` > ?',
                'expectedBindings' => [33],
            ],
            'It returns an ">=" SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '>=',
                ],
                'expectedClause' => 'where `team_lead_id` >= ?',
                'expectedBindings' => [33],
            ],
            'It returns an "<" SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '<',
                ],
                'expectedClause' => 'where `team_lead_id` < ?',
                'expectedBindings' => [33],
            ],
            'It returns an "<=" SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => 33,
                    'comparisonOperator' => '<=',
                ],
                'expectedClause' => 'where `team_lead_id` <= ?',
                'expectedBindings' => [33],
            ],
            'It returns an "between" SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => [10,33],
                    'comparisonOperator' => 'bt',
                ],
                'expectedClause' => 'where `team_lead_id` between ? and ?',
                'expectedBindings' => [10,33],
            ],
            'It returns an "IN" SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => [10,33,55,66,77,88,99],
                    'comparisonOperator' => 'in',
                ],
                'expectedClause' => 'where `team_lead_id` in (?, ?, ?, ?, ?, ?, ?)',
                'expectedBindings' => [10,33,55,66,77,88,99],
            ],
            'It returns an "NOT IN" SQL string' => [
                'data' => [
                    'columnName' => 'team_lead_id',
                    'int' => [10,33,55,66,77,88,99],
                    'comparisonOperator' => 'notin',
                ],
                'expectedClause' => 'where `team_lead_id` not in (?, ?, ?, ?, ?, ?, ?)',
                'expectedBindings' => [10,33,55,66,77,88,99],
            ],
        ];
    }
}
