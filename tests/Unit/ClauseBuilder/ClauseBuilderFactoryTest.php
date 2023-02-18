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
    protected $clauseBuilderFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clauseBuilderFactory = new ClauseBuilderFactory();
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_string_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('string');

        $this->assertInstanceOf(StringWhereClauseBuilder::class, $clauseBuilder);
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_date_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('date');

        $this->assertInstanceOf(DateWhereClauseBuilder::class, $clauseBuilder);
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_int_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('int');

        $this->assertInstanceOf(IntWhereClauseBuilder::class, $clauseBuilder);
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_float_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('float');

        $this->assertInstanceOf(FloatWhereClauseBuilder::class, $clauseBuilder);
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_json_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('json');

        $this->assertInstanceOf(JsonWhereClauseBuilder::class, $clauseBuilder);
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_order_by_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('orderBy');

        $this->assertInstanceOf(OrderByClauseBuilder::class, $clauseBuilder);
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_select_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('select');

        $this->assertInstanceOf(SelectClauseBuilder::class, $clauseBuilder);
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_includes_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('includes');

        $this->assertInstanceOf(IncludesClauseBuilder::class, $clauseBuilder);
    }

    /**
     * @group get
     * @group rest
     * @group context
     */
    public function test_creation_of_method_calls_clause_builder_class(): void
    {
        $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem('methodCalls');

        $this->assertInstanceOf(MethodCallsClauseBuilder::class, $clauseBuilder);
    }
}
