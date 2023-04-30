<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;
use Tests\TestCase;

class ErrorCollectorTest extends TestCase
{
    protected $errorCollector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->errorCollector = new ErrorCollector();
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_that_we_can_add_one_error(): void
    {
        $error = ['error'];

        $this->errorCollector->add($error);

        $this->assertEquals([$error], $this->errorCollector->getErrors());
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_that_we_can_add_two_errors(): void
    {
        $error = ['error'];

        $this->errorCollector->add($error);
        $this->errorCollector->add($error);

        $this->assertEquals([$error,$error], $this->errorCollector->getErrors());
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_we_can_get_back_an_empty_array_no_errors(): void
    {
        $this->assertEquals([], $this->errorCollector->getErrors());
    }
}