<?php

namespace Tests\Unit\ParameterDataProvider;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\StringParameterDataProvider;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\JsonParameterDataProvider;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\DateParameterDataProvider;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\IntParameterDataProvider;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\FloatParameterDataProvider;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviderFactory;
use Tests\TestCase;

class ParameterDataProviderFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->parameterDataProviderFactory = new ParameterDataProviderFactory();
    }

    /**
     * @dataProvider stringParameterProvider
     */
    public function test_creation_of_string_parameter_data_provider_class($dataType)
    {
        $parameterDataProvider = $this->parameterDataProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(StringParameterDataProvider::class, $parameterDataProvider);
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
    public function test_creation_of_date_parameter_data_provider_class($dataType)
    {
        $parameterDataProvider = $this->parameterDataProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(DateParameterDataProvider::class, $parameterDataProvider);
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
    public function test_creation_of_int_parameter_data_provider_class($dataType)
    {
        $parameterDataProvider = $this->parameterDataProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(IntParameterDataProvider::class, $parameterDataProvider);
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
    public function test_creation_of_float_parameter_data_provider_class($dataType)
    {
        $parameterDataProvider = $this->parameterDataProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(FloatParameterDataProvider::class, $parameterDataProvider);
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

    public function test_creation_of_json_parameter_data_provider_class()
    {
        $parameterDataProvider = $this->parameterDataProviderFactory->getFactoryItem('json');

        $this->assertInstanceOf(JsonParameterDataProvider::class, $parameterDataProvider);
    }
}
