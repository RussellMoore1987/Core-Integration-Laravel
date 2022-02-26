<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use Tests\TestCase;

class DataTypeDeterminerFactoryTest extends TestCase
{
    private $endpointData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dataTypeDeterminerFactory = new DataTypeDeterminerFactory();
    }

    // tests ------------------------------------------------------------
    /**
     * @dataProvider stringDataTypeProvider
     */
    public function test_for_data_type_of_string($dataType)
    {
        $commonDataType = $this->dataTypeDeterminerFactory->getFactoryItem($dataType);

        $this->assertEquals('string', $commonDataType);
    }
    public function stringDataTypeProvider()
    {
        return [
            'varchar' => ['Varchar'],
            'char' => ['char'],
            'blob' => ['blob'],
            'text' => ['text'],
        ];
    }

    /**
     * @dataProvider dateDataTypeProvider
     */
    public function test_for_data_type_of_date($dataType)
    {
        $commonDataType = $this->dataTypeDeterminerFactory->getFactoryItem($dataType);

        $this->assertEquals('date', $commonDataType);
    }
    public function dateDataTypeProvider()
    {
        return [
            'date' => ['date'],
            'timestamp' => ['Timestamp'],
            'datetime' => ['datetime'],
        ];
    }

    /**
     * @dataProvider intDataTypeProvider
     */
    public function test_for_data_type_of_int($dataType)
    {
        $commonDataType = $this->dataTypeDeterminerFactory->getFactoryItem($dataType);

        $this->assertEquals('int', $commonDataType);
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
    public function test_for_data_type_of_float($dataType)
    {
        $commonDataType = $this->dataTypeDeterminerFactory->getFactoryItem($dataType);

        $this->assertEquals('float', $commonDataType);
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

    public function test_for_data_type_of_order_by()
    {
        $commonDataType = $this->dataTypeDeterminerFactory->getFactoryItem('orderBy');

        $this->assertEquals('orderby', $commonDataType);
    }

    public function test_for_data_type_of_select()
    {
        $commonDataType = $this->dataTypeDeterminerFactory->getFactoryItem('select');

        $this->assertEquals('select', $commonDataType);
    }

    public function test_for_data_type_of_includes()
    {
        $commonDataType = $this->dataTypeDeterminerFactory->getFactoryItem('includes');

        $this->assertEquals('includes', $commonDataType);
    }

    public function test_for_data_type_of_method_calls()
    {
        $commonDataType = $this->dataTypeDeterminerFactory->getFactoryItem('methodCalls');

        $this->assertEquals('methodcalls', $commonDataType);
    }
}
