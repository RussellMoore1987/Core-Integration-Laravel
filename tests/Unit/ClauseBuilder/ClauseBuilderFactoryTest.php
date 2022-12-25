<?php

namespace Tests\Unit\ClauseBuilder;

use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\StringWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\JsonWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\DateWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\IntWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\FloatWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\OrderByClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\SelectClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\IncludesClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\MethodCallsClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory;

use Tests\TestCase;

class ClauseBuilderFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->clauseBuilderFactory = new ClauseBuilderFactory();
    }

    // tests ------------------------------------------------------------
    /**
     * @dataProvider stringDataTypeProvider
     */
    public function test_creation_of_string_clause_builder_class($dataType)
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(StringWhereClauseBuilder::class, $clauseBuilder);
    }
    public function stringDataTypeProvider()
    {
        return [
            'varchar' => ['Varchar'],
            'char' => ['char'],
            'blob' => ['blob'],
            'text' => ['Text'],
        ];
    }

    /**
     * @dataProvider dateDataTypeProvider
     */
    public function test_creation_of_date_clause_builder_class($dataType)
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(DateWhereClauseBuilder::class, $clauseBuilder);
    }
    public function dateDataTypeProvider()
    {
        return [
            'date' => ['date'],
            'timestamp' => ['Timestamp'],
            'datetime' => ['Datetime'],
        ];
    }

    /**
     * @dataProvider intDataTypeProvider
     */
    public function test_creation_of_int_clause_builder_class($dataType)
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(IntWhereClauseBuilder::class, $clauseBuilder);
    }
    public function intDataTypeProvider()
    {
        return [
            'integer' => ['integer'],
            'int' => ['Int'],
            'smallint' => ['smallint'],
            'tinyint' => ['tinyint'],
            'mediumint' => ['Mediumint'],
            'bigint' => ['bigint'],
        ];
    }

    /**
     * @dataProvider floatDataTypeProvider
     */
    public function test_creation_of_float_clause_builder_class($dataType)
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(FloatWhereClauseBuilder::class, $clauseBuilder);
    }
    public function floatDataTypeProvider()
    {
        return [
            'decimal' => ['decimal'],
            'numeric' => ['numeric'],
            'float' => ['Float'],
            'double' => ['double'],
        ];
    }

    public function test_creation_of_json_clause_builder_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('json');

        $this->assertInstanceOf(JsonWhereClauseBuilder::class, $clauseBuilder);
    }

    public function test_creation_of_order_by_clause_builder_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('orderBy');

        $this->assertInstanceOf(OrderByClauseBuilder::class, $clauseBuilder);
    }

    public function test_creation_of_select_clause_builder_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('select');

        $this->assertInstanceOf(SelectClauseBuilder::class, $clauseBuilder);
    }

    public function test_creation_of_includes_clause_builder_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('includes');

        $this->assertInstanceOf(IncludesClauseBuilder::class, $clauseBuilder);
    }

    public function test_creation_of_method_calls_clause_builder_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('methodCalls');

        $this->assertInstanceOf(MethodCallsClauseBuilder::class, $clauseBuilder);
    }
}
