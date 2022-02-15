<?php

namespace Tests\Unit;

// ! look over all files and names *************************************************** 5 files

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
     * @dataProvider stringParameterProvider
     */
    public function test_creation_of_string_parameter_validator_class($dataType)
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(StringWhereClauseBuilder::class, $clauseBuilder);
    }
    public function stringParameterProvider()
    {
        return [
            'varchar' => ['Varchar'],
            'char' => ['char'],
            'blob' => ['blob'],
            'text' => ['text'],
        ];
    }

    /**
     * @dataProvider dateParameterProvider
     */
    public function test_creation_of_date_parameter_validator_class($dataType)
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(DateWhereClauseBuilder::class, $clauseBuilder);
    }
    public function dateParameterProvider()
    {
        return [
            'date' => ['date'],
            'timestamp' => ['Timestamp'],
            'datetime' => ['datetime'],
        ];
    }

    /**
     * @dataProvider intParameterProvider
     */
    public function test_creation_of_int_parameter_validator_class($dataType)
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(IntWhereClauseBuilder::class, $clauseBuilder);
    }
    public function intParameterProvider()
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
     * @dataProvider floatParameterProvider
     */
    public function test_creation_of_float_parameter_validator_class($dataType)
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(FloatWhereClauseBuilder::class, $clauseBuilder);
    }
    public function floatParameterProvider()
    {
        return [
            'decimal' => ['decimal'],
            'numeric' => ['numeric'],
            'float' => ['Float'],
            'double' => ['double'],
        ];
    }

    public function test_creation_of_id_parameter_validator_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('id');

        $this->assertInstanceOf(IdClauseBuilder::class, $clauseBuilder);
    }

    public function test_creation_of_order_by_parameter_validator_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('orderBy');

        $this->assertInstanceOf(OrderByClauseBuilder::class, $clauseBuilder);
    }

    public function test_creation_of_select_parameter_validator_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('select');

        $this->assertInstanceOf(SelectClauseBuilder::class, $clauseBuilder);
    }

    public function test_creation_of_includes_parameter_validator_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('includes');

        $this->assertInstanceOf(IncludesClauseBuilder::class, $clauseBuilder);
    }

    public function test_creation_of_method_calls_parameter_validator_class()
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('methodCalls');

        $this->assertInstanceOf(MethodCallsClauseBuilder::class, $clauseBuilder);
    }
}
