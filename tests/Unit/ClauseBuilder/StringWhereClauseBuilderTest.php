<?php

namespace Tests\Unit\ClauseBuilder;

use App\CoreIntegrationApi\CIL\ClauseBuilder\StringWhereClauseBuilder;
use App\Exceptions\ClauseBuilderException;
use App\Models\Project;
use Tests\TestCase;

class StringWhereClauseBuilderTest extends TestCase
{
    private $queryBuilder;
    private $existingQueryBuilder;
    private $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->existingQueryBuilder = Project::query();
        $this->queryBuilder = new StringWhereClauseBuilder;
        $this->data = [
            'columnName' => 'some_column',
            'string' => 'Some String'
        ];
    }

    /**
     * @test
     *
     * @covers ::build
     */
    public function it_throws_an_exception_if_there_is_no_column_name_in_the_data()
    {
        unset($this->data['columnName']);

        $this->expectException(ClauseBuilderException::class);

        $this->queryBuilder->build($this->existingQueryBuilder, $this->data);
    }

    /**
     * @test
     *
     * @covers ::build
     */
    public function it_throws_an_exception_if_there_is_no_string_in_the_data()
    {
        unset($this->data['string']);

        $this->expectException(ClauseBuilderException::class);

        $this->queryBuilder->build($this->existingQueryBuilder, $this->data);
    }

    /**
     * @test
     *
     * @covers ::build
     * @dataProvider dataSetup
     */
    public function test_it_builds_the_correct_query($string, $columnName, $expectedClause, $expectedBindings)
    {
        $this->data = [
            "string" => $string,
            "columnName" => $columnName
        ];

        $resultQueryBuilder = $this->queryBuilder->build($this->existingQueryBuilder, $this->data);

        $this->assertStringContainsString($expectedClause, $resultQueryBuilder->toSql());
        $this->assertEquals($expectedBindings, $resultQueryBuilder->getBindings());
    }

    public function dataSetup()
    {
        $columnName = "some_column";

        return [
            "It parses a single string when building the query" => [
                "string" => "ted",
                "columnName" => $columnName,
                "expectedClause" => "where lower(`${columnName}`) like ?",
                "expectedBindings" => ['%ted%']
            ],
            "It parses a comma separated string when building the query" => [
                "string" => "ted,tom,fred",
                "columnName" => $columnName,
                "expectedClause" => "where lower(`${columnName}`) like ? or lower(`${columnName}`) like ? or lower(`${columnName}`) like ?",
                "expectedBindings" => ['%ted%', "%tom%", "%fred%"]
            ],
            "It parses a comma separated string with whitespaces when building the query" => [
                "string" => "ted meyers,tom jones,fred hanks",
                "columnName" => $columnName,
                "expectedClause" => "where lower(`${columnName}`) like ? or lower(`${columnName}`) like ? or lower(`${columnName}`) like ?",
                "expectedBindings" => ["%ted meyers%", "%tom jones%", "%fred hanks%"]
            ],
            "It parses a single string with the exact symbol when building the query" => [
                "string" => "ted::exact",
                "columnName" => $columnName,
                "expectedClause" => "where lower(`${columnName}`) = ?",
                "expectedBindings" => ["ted"]
            ],
            "It parses a comma separated string with exact symbols when building the query" => [
                "string" => "ted::exact,fred::exact,jones::exact",
                "columnName" => $columnName,
                "expectedClause" => "where lower(`${columnName}`) = ? or lower(`${columnName}`) = ? or lower(`${columnName}`) = ?",
                "expectedBindings" => ["ted", "fred", "jones"]
            ],
            "It parses a comma separated string with incorrect exact symbol usage when building the query" => [
                "string" => "::exactmathew::exact",
                "columnName" => $columnName,
                "expectedClause" => "where lower(`${columnName}`) = ?",
                "expectedBindings" => ["mathew"]
            ],
            "It parses strings with a mix of uppercase letters and exact symbols correctly when building the query" => [
                "string" => "Ted::exact,FRED,jOnEs::exact",
                "columnName" => $columnName,
                "expectedClause" => "where lower(`${columnName}`) = ? or lower(`${columnName}`) like ? or lower(`${columnName}`) = ?",
                "expectedBindings" => ["ted", "%fred%", "jones"]
            ],
        ];
    }
}
