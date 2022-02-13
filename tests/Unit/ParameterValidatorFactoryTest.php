<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\FloatParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IdParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\OrderByParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\SelectParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IncludesParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\MethodCallsParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use Tests\TestCase;

class ParameterValidatorFactoryTest extends TestCase
{
    private $endpointData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ParameterValidatorFactory = new ParameterValidatorFactory();
    }

    // tests ------------------------------------------------------------
    /**
     * @dataProvider stringParameterProvider
     */
    public function test_creation_of_string_parameter_validator_class($dataType)
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(StringParameterValidator::class, $parameterValidator);
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
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(DateParameterValidator::class, $parameterValidator);
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
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(IntParameterValidator::class, $parameterValidator);
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
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(FloatParameterValidator::class, $parameterValidator);
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
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('id');

        $this->assertInstanceOf(IdParameterValidator::class, $parameterValidator);
    }

    public function test_creation_of_order_by_parameter_validator_class()
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('orderBy');

        $this->assertInstanceOf(OrderByParameterValidator::class, $parameterValidator);
    }

    public function test_creation_of_select_parameter_validator_class()
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('select');

        $this->assertInstanceOf(SelectParameterValidator::class, $parameterValidator);
    }

    public function test_creation_of_includes_parameter_validator_class()
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('includes');

        $this->assertInstanceOf(IncludesParameterValidator::class, $parameterValidator);
    }

    public function test_creation_of_method_calls_parameter_validator_class()
    {
        $parameterValidator = $this->ParameterValidatorFactory->getFactoryItem('methodCalls');

        $this->assertInstanceOf(MethodCallsParameterValidator::class, $parameterValidator);
    }
}
