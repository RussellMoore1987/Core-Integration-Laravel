<?php

namespace Tests\Unit;

use App\QueryBuilders\StringQueryBuilder;
use App\Exceptions\EmptyBindingsException;
use App\Exceptions\ColumnNotSetException;
use App\Exceptions\ModelNotSetException;
use Tests\TestCase;

class StringQueryBuilderTest extends TestCase
{
    private $queryBuilder;
    private $model = 'App\Models\Skill';
    private $table = 'skills';
    private $column = 'name';

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBuilder = new StringQueryBuilder;
        $this->queryBuilder->setModel($this->model);
        $this->queryBuilder->setColumn($this->column);
    }

    public function test_it_can_parse_a_single_string()
    {
        $string = 'Ted';
        $expected = ["%ted%"];

        $actual = $this->queryBuilder->parse($string);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function test_it_can_parse_a_comma_separated_string()
    {
        $string = 'Ted,Tom,Fred';
        $expected = ["%Ted%", "%Tom%", "%Fred%"];

        $actual = $this->queryBuilder->parse($string);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function test_it_can_parse_a_comma_separated_string_with_whitespaces()
    {
        $string = 'Ted Meyers,Tom Jones,Fred Hanks';
        $expected = ["%Ted Meyers%", "%Tom Jones%", "%Fred Hanks%"];

        $actual = $this->queryBuilder->parse($string);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function test_it_can_parse_a_single_string_with_exact_symbol()
    {
        $string = 'Ted::exact';
        $expected = ["Ted"];

        $actual = $this->queryBuilder->parse($string);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function test_it_can_parse_comma_separated_string_with_exact_symbols()
    {
        $string = 'Ted::exact,Fred::exact,Jones::exact';
        $expected = ["Ted", "Fred", "Jones"];

        $actual = $this->queryBuilder->parse($string);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function test_it_can_parse_string_with_incorrect_exact_symbol_usage()
    {
        $string = '::exactTed::exact';
        $expected = ["Ted"];

        $actual = $this->queryBuilder->parse($string);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    // TODO: Use DataProviders for these test cases to clean up this code.....
    public function test_it_can_build_a_query_with_a_single_param()
    {
        $string = 'Ted';

        $expectedSql = "select * from `$this->table` where `$this->column` like ?";
        $expectedBindings = ["%Ted%"];

        $this->queryBuilder->parse($string);

        $query = $this->queryBuilder->build();

        $this->assertStringContainsStringIgnoringCase($expectedSql, $query->toSql());
        $this->assertEquals($expectedBindings, $query->getBindings());
    }

    public function test_it_can_build_a_query_with_multiple_params()
    {
        $string = 'Ted,Fred';

        $expectedSql = "select * from `$this->table` where `$this->column` like ? or `$this->column` like ?";
        $expectedBindings = ["%Ted%", "%Fred%"];

        $this->queryBuilder->parse($string);
        $query = $this->queryBuilder->build();

        $this->assertStringContainsStringIgnoringCase($expectedSql, $query->toSql());
        $this->assertEquals($expectedBindings, $query->getBindings());
    }

    public function test_it_can_build_a_query_with_single_exact_param()
    {
        $string = 'Ted::exact';

        $expectedSql = "select * from `$this->table` where `$this->column` = ?";
        $expectedBindings = ["Ted"];

        $this->queryBuilder->parse($string);
        $query = $this->queryBuilder->build();

        $this->assertStringContainsStringIgnoringCase($expectedSql, $query->toSql());
        $this->assertEquals($expectedBindings, $query->getBindings());
    }

    public function test_it_can_build_a_complex_string_query()
    {
        $string = 'Ted::exact,Fred,Ned::exact,Edward,Thadeus::exact';

        $expectedSql = "select * from `$this->table` where `$this->column` = ? or `$this->column` like ? or `$this->column` = ? or `$this->column` like ? or `$this->column` = ?";
        $expectedBindings = ["Ted", "%Fred%", "Ned", "%Edward%", "Thadeus"];

        $this->queryBuilder->parse($string);
        $query = $this->queryBuilder->build();

        $this->assertStringContainsStringIgnoringCase($expectedSql, $query->toSql());
        $this->assertEquals($expectedBindings, $query->getBindings());
    }

    public function test_it_throws_an_exception_if_there_are_no_bindings_set_when_building_the_query()
    {
        $this->expectException(EmptyBindingsException::class);

        $this->queryBuilder->build();
    }

    public function test_it_throws_an_exception_if_there_is_no_column_set_when_building_the_query()
    {
        $queryBuilder = new StringQueryBuilder;
        $queryBuilder->setModel($this->model);

        $string = 'Ted,Fred';

        $queryBuilder->parse($string);

        $this->expectException(ColumnNotSetException::class);

        $queryBuilder->build();
    }

    public function test_it_throws_an_exception_if_there_is_no_model_set_when_building_the_query()
    {
        $queryBuilder = new StringQueryBuilder;
        $queryBuilder->setColumn($this->column);

        $string = 'Ted,Fred';

        $queryBuilder->parse($string);

        $this->expectException(ModelNotSetException::class);

        $queryBuilder->build();
    }
}
