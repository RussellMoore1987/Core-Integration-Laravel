<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\StringResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\JsonResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\DateResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\IntResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\FloatResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviderFactory;
use Tests\TestCase;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (I have a stash of tests**** EndpointValidatorTest.php) (sub IntResourceParameterInfoProvider DateResourceParameterInfoProvider)
// [] read over
// [] test groups, rest, context
// [] add return type : void
// [] testing what I need to test

class ResourceParameterInfoProviderFactoryTest extends TestCase
{
    protected $resourceParameterInfoProviderFactory;

    protected function setUp() : void
    {
        parent::setUp();

        $this->resourceParameterInfoProviderFactory = new ResourceParameterInfoProviderFactory();
    }

    /**
     * @dataProvider stringParameterProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_creation_of_StringResourceParameterInfoProvider_class($dataType) : void
    {
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(StringResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
    }
    public function stringParameterProvider() : array
    {
        // TODO: other string type, is there a way to ask the database what the constraints are???
        return [
            'varchar' => ['Varchar'],
            'char' => ['char'],
            'blob' => ['blob'],
            'text' => ['text'],
        ];
    }

    /**
     * @dataProvider dateParameterProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_creation_of_DateResourceParameterInfoProvider_class($dataType) : void
    {
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(DateResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
    }
    public function dateParameterProvider() : array
    {
        // TODO: other date types
        return [
            'date' => ['date'],
            'timestamp' => ['Timestamp'],
            'datetime' => ['datetime'],
        ];
    }

    /**
     * @dataProvider intParameterProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_creation_of_IntResourceParameterInfoProvider_class($dataType) : void
    {
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(IntResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
    }
    public function intParameterProvider() : array
    {
        // TODO: add bit ???
        return [
            'integer' => ['integer'],
            'int' => ['Int'],
            'smallint' => ['smallint'],
            'tinyint' => ['tinyint'],
            'mediumint' => ['Mediumint'],
            'bigint' => ['bigint'],
            'integer unsigned' => ['integer unsigned'],
            'int unsigned' => ['Int unsigned'],
            'smallint unsigned' => ['smallint unsigned'],
            'tinyint unsigned' => ['tinyint unsigned'],
            'mediumint unsigned' => ['Mediumint unsigned'],
            'bigint unsigned' => ['bigint unsigned'],
        ];
    }

    /**
     * @dataProvider floatParameterProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_creation_of_FloatResourceParameterInfoProvider_class($dataType) : void
    {
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($dataType);

        $this->assertInstanceOf(FloatResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
    }
    public function floatParameterProvider() : array
    {
        return [
            'decimal' => ['decimal'],
            'numeric' => ['numeric'],
            'float' => ['Float'],
            'double' => ['double'],
            'decimal unsigned' => ['decimal unsigned'],
            'numeric unsigned' => ['numeric unsigned'],
            'float unsigned' => ['Float unsigned'],
            'double unsigned' => ['double unsigned'],
        ];
    }

    /**
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_creation_of_JsonResourceParameterInfoProvider_class() : void
    {
        $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem('json');

        $this->assertInstanceOf(JsonResourceParameterInfoProvider::class, $resourceParameterInfoProvider);
    }
}
