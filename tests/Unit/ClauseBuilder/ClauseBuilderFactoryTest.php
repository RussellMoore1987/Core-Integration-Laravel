<?php

namespace Tests\Unit\ClauseBuilder;

use App\CoreIntegrationApi\CIL\ClauseBuilder\StringWhereClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\DateWhereClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\IntWhereClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\FloatWhereClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\IdClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\OrderByClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\SelectClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\IncludesClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\MethodCallsClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilderFactory;

use Tests\TestCase;

class ClauseBuilderFactoryTest extends TestCase
{
    private $endpointData;

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

    public function test_creation_of_id_clause_builder_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('id');

        $this->assertInstanceOf(IdClauseBuilder::class, $clauseBuilder);
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
