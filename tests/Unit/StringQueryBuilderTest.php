<?php

namespace Tests\Unit;

use App\QueryBuilders\StringQueryBuilder;

use Tests\TestCase;

// TODO: Parse param=string
// TODO: Parse param=string,string
// TODO: Parse param=string%
// TODO: Parse param=%string
// TODO: Parse param=%string%
// TODO: Parse param=%string%,%string
// TODO: Edge cases for building the query
// TODO: Edge cases for parsing the string

class StringQueryBuilderTest extends TestCase
{
    private $queryBuilder;
    private $testModel = 'App\Models\Skill';
    private $testTableName = 'skills';

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBuilder = new StringQueryBuilder;
        $this->queryBuilder->setModel($this->testModel);
    }

    public function test_it_can_be_instantiated()
    {
        $this->assertInstanceOf(StringQueryBuilder::class, $this->queryBuilder);
    }

    public function test_it_can_parse_a_single_string()
    {
        $testString = 'Ted';
        $expected = ["Ted"];

        $actual = $this->queryBuilder->parse($testString);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function test_it_can_parse_a_comma_separated_string()
    {
        $testString = 'Ted,Tom,Fred';
        $expected = ["Ted", "Tom", "Fred"];

        $actual = $this->queryBuilder->parse($testString);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function test_it_can_parse_a_comma_separated_string_with_whitespaces()
    {
        $testString = 'Ted, Tom, Fred ';
        $expected = ["Ted", "Tom", "Fred"];

        $actual = $this->queryBuilder->parse($testString);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    // TODO: May not need this test case....
    public function test_it_can_parse_a_single_string_with_percent_signs()
    {
        $testString = '%Ted';
        $expected = ["%Ted"];

        $actual = $this->queryBuilder->parse($testString);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    // TODO: Use DataProviders for these test cases to clean up this code.....
    public function test_it_can_build_a_query_with_a_single_param()
    {
        $testString = 'Ted';
        $column = 'name';

        $expectedSql = "select * from `$this->testTableName` where `$column` = ?";
        $expectedBindings = ["Ted"];

        $this->queryBuilder->parse($testString);

        $query = $this->queryBuilder->build($column);

        $this->assertStringContainsStringIgnoringCase($expectedSql, $query->toSql());
        $this->assertEquals($expectedBindings, $query->getBindings());
    }

    public function test_it_can_build_a_query_with_multiple_params()
    {
        $testString = 'Ted,Fred';
        $column = 'name';

        $expectedSql = "select * from `$this->testTableName` where `$column` = ? or `$column` = ?";
        $expectedBindings = ["Ted", "Fred"];

        $this->queryBuilder->parse($testString);
        $query = $this->queryBuilder->build($column);

        $this->assertStringContainsStringIgnoringCase($expectedSql, $query->toSql());
        $this->assertEquals($expectedBindings, $query->getBindings());
    }

    public function test_it_can_build_a_query_with_single_like_param()
    {
        $testString = '%Ted';
        $column = 'name';

        $expectedSql = "select * from `$this->testTableName` where `$column` like ?";
        $expectedBindings = ["%Ted"];

        $this->queryBuilder->parse($testString);
        $query = $this->queryBuilder->build($column);

        $this->assertStringContainsStringIgnoringCase($expectedSql, $query->toSql());
        $this->assertEquals($expectedBindings, $query->getBindings());
    }

    public function test_it_can_build_a_complex_string_query()
    {
        $testString = '%Ted,Fred,Ned%,Edward,%Thadeus%';
        $column = 'name';

        $expectedSql = "select * from `$this->testTableName` where `$column` like ? or `$column` = ? or `$column` like ? or `$column` = ? or `$column` like ?";
        $expectedBindings = ["%Ted", "Fred", "Ned%", "Edward", "%Thadeus%"];

        $this->queryBuilder->parse($testString);
        $query = $this->queryBuilder->build($column);

        $this->assertStringContainsStringIgnoringCase($expectedSql, $query->toSql());
        $this->assertEquals($expectedBindings, $query->getBindings());
    }
}
