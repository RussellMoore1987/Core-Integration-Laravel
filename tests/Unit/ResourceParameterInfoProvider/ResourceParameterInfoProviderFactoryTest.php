<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\StringResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\JsonResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\DateResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\IntResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\FloatResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviderFactory;
use Tests\TestCase;

class ResourceParameterInfoProviderFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->resourceParameterInfoProviderFactory = new ResourceParameterInfoProviderFactory();
    }

    /**
     * @dataProvider stringParameterProvider
     */
    public function test_creation_of_string_parameter_data_provider_class($dataType)
    {
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(StringResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
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
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(DateResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
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
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(IntResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
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
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(FloatResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
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
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem('json');

        $this->assertInstanceOf(JsonResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
    }
}
