<?php

namespace Tests\Unit\ClauseBuilder;

use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\DateWhereClauseBuilder;
use App\Models\Project;
use Tests\TestCase;

class DateWhereClauseBuilderTest extends TestCase
{
    protected $queryBuilder;
    protected $dateWhereClauseBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBuilder = Project::query();

        $this->dateWhereClauseBuilder = new DateWhereClauseBuilder();
    }

    /**
     * @test
     *
     * @covers ::build
     * @dataProvider dataSetup
     */
    public function test_it_builds_the_correct_query($data, $expectedClause, $expectedBindings)
    {
        $resultQueryBuilder = $this->dateWhereClauseBuilder->build($this->queryBuilder, $data);

        $this->assertStringContainsString($expectedClause, $resultQueryBuilder->toSql());
        $this->assertEquals($expectedBindings, $resultQueryBuilder->getBindings());
    }

    public function dataSetup()
    {
        return [
            'It returns an "=" SQL string' => [
                'data' => [
                    'columnName' => 'start_date',
                    'date' => '2020-03-20',
                    'comparisonOperator' => '=',
                ],
                'expectedClause' => 'where date(`start_date`) = ?',
                'expectedBindings' => ['2020-03-20'],
            ],
            'It returns an ">" SQL string' => [
                'data' => [
                    'columnName' => 'start_date',
                    'date' => 2020-03-20,
                    'comparisonOperator' => '>',
                ],
                'expectedClause' => 'where date(`start_date`) > ?',
                'expectedBindings' => [2020-03-20],
            ],
            'It returns an ">=" SQL string' => [
                'data' => [
                    'columnName' => 'start_date',
                    'date' => 2020-03-20,
                    'comparisonOperator' => '>=',
                ],
                'expectedClause' => 'where date(`start_date`) >= ?',
                'expectedBindings' => [2020-03-20],
            ],
            'It returns an "<" SQL string' => [
                'data' => [
                    'columnName' => 'start_date',
                    'date' => 2020-03-20,
                    'comparisonOperator' => '<',
                ],
                'expectedClause' => 'where date(`start_date`) < ?',
                'expectedBindings' => [2020-03-20],
            ],
            'It returns an "<=" SQL string' => [
                'data' => [
                    'columnName' => 'start_date',
                    'date' => 2020-03-20,
                    'comparisonOperator' => '<=',
                ],
                'expectedClause' => 'where date(`start_date`) <= ?',
                'expectedBindings' => [2020-03-20],
            ],
            'It returns an "between" SQL string' => [
                'data' => [
                    'columnName' => 'start_date',
                    'date' => [2020-03-20,2020-03-21],
                    'comparisonOperator' => 'bt',
                ],
                'expectedClause' => 'where `start_date` between ? and ?',
                'expectedBindings' => [2020-03-20,2020-03-21],
            ],
        ];
    }
}
