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
    protected function setUp(): void
    {
        parent::setUp();

        $this->ParameterValidatorFactory = new ParameterValidatorFactory();
    }

    // tests ------------------------------------------------------------
    /**
     * @dataProvider stringParameterProvider
     * @group getMethod
     */
    public function test_creation_of_string_parameter_validator_class($dataType)  : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(StringParameterValidator::class, $parameterValidator);
    }
    public function stringParameterProvider() : array
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
     * @group getMethod
     */
    public function test_creation_of_date_parameter_validator_class($dataType) : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(DateParameterValidator::class, $parameterValidator);
    }
    public function dateParameterProvider() : array
    {
        return [
            'date' => ['date'],
            'timestamp' => ['Timestamp'],
            'datetime' => ['datetime'],
        ];
    }

    /**
     * @dataProvider intParameterProvider
     * @group getMethod
     */
    public function test_creation_of_int_parameter_validator_class($dataType) : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(IntParameterValidator::class, $parameterValidator);
    }
    public function intParameterProvider() : array
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
     * @group getMethod
     */
    public function test_creation_of_float_parameter_validator_class($dataType) : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(FloatParameterValidator::class, $parameterValidator);
    }
    public function floatParameterProvider() : array
    {
        return [
            'decimal' => ['decimal'],
            'numeric' => ['numeric'],
            'float' => ['Float'],
            'double' => ['double'],
        ];
    }

    /**
     * @group getMethod
     */
    public function test_creation_of_json_parameter_validator_class() : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('json');

        $this->assertInstanceOf(JsonParameterValidator::class, $parameterValidator);
    }

    /**
     * @group getMethod
     */
    public function test_creation_of_order_by_parameter_validator_class() : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('orderBy');

        $this->assertInstanceOf(OrderByParameterValidator::class, $parameterValidator);
    }

    /**
     * @group getMethod
     */
    public function test_creation_of_select_parameter_validator_class() : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('select');

        $this->assertInstanceOf(SelectParameterValidator::class, $parameterValidator);
    }

    /**
     * @group getMethod
     */
    public function test_creation_of_includes_parameter_validator_class() : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('includes');

        $this->assertInstanceOf(IncludesParameterValidator::class, $parameterValidator);
    }

    /**
     * @group getMethod
     */
    public function test_creation_of_method_calls_parameter_validator_class() : void
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('methodCalls');

        $this->assertInstanceOf(MethodCallsParameterValidator::class, $parameterValidator);
    }
}
