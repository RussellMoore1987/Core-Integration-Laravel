<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\FloatResourceParameterInfoProvider;

use Tests\TestCase;

class FloatResourceParameterInfoProviderTest extends TestCase
{
    protected $floatResourceParameterInfoProvider;
    protected $parameterAttributeArray;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parameterAttributeArray = [
            'field' => 'fakeParameterName',
            'type' => 'decimal(10,2)',
            'null' => 'no',
            'key' => '',
            'default' => '0',
            'extra' => '',
        ];

        $this->floatResourceParameterInfoProvider = new FloatResourceParameterInfoProvider();
    }

    /**
     * @dataProvider decimalResourceParameterInfoProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_floatResourceParameterInfoProvider_returns_correct_values(string $parameterType, array $expectedResultPieces): void
    {
        $this->parameterAttributeArray['type'] = $parameterType;
        $result = $this->floatResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);

        $expectedResultPieces['defaultValidationRules'] = [
            'numeric',
            'min: ' . $expectedResultPieces['min'],
            'max: ' . $expectedResultPieces['max'],
        ];

        $this->assertEquals($this->getExpectedResult($expectedResultPieces), $result);
    }

    // TODO make sure tests fail
    public function decimalResourceParameterInfoProvider(): array
    {
        return [
            'defaultDecimal' => [
                'decimal',
                [
                    'min' => -9999999999,
                    'max' => 9999999999,
                ],
            ],
            // 'defaultDecimalUnsigned' => [ // decimal(5,5) unsigned
            //     'decimal unsigned', // what does this look like in the database?
            //     [
            //         'min' => 0,
            //         'max' => 9999999999,
            //     ],
            // ],
            'decimalWithPrecision_10,2' => [
                'decimal(10,2)',
                [
                    'min' => -99999999.99,
                    'max' => 99999999.99,
                ],
            ],
            // 'decimalWithPrecision_10,2Unsigned' => [
            //     'decimal(10,2) unsigned',
            //     [
            //         'min' => 0,
            //         'max' => 9999999999.99,
            //     ],
            // ],
            'decimalWithPrecision_10,0' => [
                'decimal(10,0)',
                [
                    'min' => -9999999999,
                    'max' => 9999999999,
                ],
            ],
            'decimalWithPrecision_5' => [
                'decimal(5)',
                [
                    'min' => -99999,
                    'max' => 99999,
                ],
            ],
            'decimalWithPrecision_5,5' => [
                'decimal(5,5)',
                [
                    'min' => -0.99999,
                    'max' => 0.99999,
                ],
            ],
            'decimalWithPrecision_5,3' => [
                'decimal(5,3)',
                [
                    'min' => -99.999,
                    'max' => 99.999,
                ],
            ],
        ];
    }

    protected function getExpectedResult(array $expectedResultPieces): array
    {
        return [
            'apiDataType' => 'float',
            'formData' => [
                'min' => $expectedResultPieces['min'],
                'max' => $expectedResultPieces['max'],
                'type' => 'number',
            ],
            'defaultValidationRules' => $expectedResultPieces['defaultValidationRules'],
        ];
    }
}
