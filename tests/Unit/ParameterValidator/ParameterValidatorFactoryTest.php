<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\JsonParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\FloatParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\OrderByParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\SelectParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IncludesParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\MethodCallsParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use Tests\TestCase;

class ParameterValidatorFactoryTest extends TestCase
{
    protected $parameterValidatorFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parameterValidatorFactory = new ParameterValidatorFactory();
    }

    /**
     * @group get
     */
    public function test_creation_of_string_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('string');

        $this->assertInstanceOf(StringParameterValidator::class, $parameterValidator);
    }

    /**
     * @group get
     */
    public function test_creation_of_date_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('date');

        $this->assertInstanceOf(DateParameterValidator::class, $parameterValidator);
    }

    /**
     * @group get
     */
    public function test_creation_of_int_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('int');

        $this->assertInstanceOf(IntParameterValidator::class, $parameterValidator);
    }

    /**
     * @group get
     */
    public function test_creation_of_float_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('float');

        $this->assertInstanceOf(FloatParameterValidator::class, $parameterValidator);
    }

    /**
     * @group get
     */
    public function test_creation_of_json_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('json');

        $this->assertInstanceOf(JsonParameterValidator::class, $parameterValidator);
    }

    /**
     * @group get
     */
    public function test_creation_of_order_by_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('orderBy');

        $this->assertInstanceOf(OrderByParameterValidator::class, $parameterValidator);
    }

    /**
     * @group get
     */
    public function test_creation_of_select_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('select');

        $this->assertInstanceOf(SelectParameterValidator::class, $parameterValidator);
    }

    /**
     * @group get
     */
    public function test_creation_of_includes_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('includes');

        $this->assertInstanceOf(IncludesParameterValidator::class, $parameterValidator);
    }

    /**
     * @group get
     */
    public function test_creation_of_method_calls_parameter_validator_class(): void
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem('methodCalls');

        $this->assertInstanceOf(MethodCallsParameterValidator::class, $parameterValidator);
    }
}
