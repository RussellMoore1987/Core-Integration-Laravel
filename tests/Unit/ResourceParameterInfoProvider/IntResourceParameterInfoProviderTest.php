<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\IntResourceParameterInfoProvider;
use Tests\TestCase;

// TODO: add validation rules to test
// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (sub IntResourceParameterInfoProvider DateResourceParameterInfoProvider)
// [] read over
// [] test groups, rest, context
// [] add return type : void
// [] testing what I need to test
// [] run exception for abstract

class IntResourceParameterInfoProviderTest extends TestCase
{
    protected $intResourceParameterInfoProvider;
    protected $parameterAttributeArray;
    protected $minValidationForUnsigned = 'min:0';
    protected $maxValidationForIntegerUnsigned = 'max:4294967295';
    protected $minValidationForInteger = 'min:-2147483648';
    protected $maxValidationForInteger = 'max:2147483647';

    protected function setUp(): void
    {
        parent::setUp();

        $this->parameterAttributeArray = [
            'field' => 'fakeParameterName',
            'type' => 'tinyint',
            'null' => 'no',
            'key' => '',
            'default' => '0',
            'extra' => '',
        ];

        $this->intResourceParameterInfoProvider = new IntResourceParameterInfoProvider();
    }

    /**
     * @dataProvider classFormDataProvider
     */
    public function test_IntResourceParameterInfoProvider_with_custom_class_form_data_returned(string $type, array $formData, array $expectedFormData): void
    {
        $this->parameterAttributeArray['type'] = $type;
        $formData = [
            'fakeParameterName' => $formData,
        ];

        $result = $this->intResourceParameterInfoProvider->getData($this->parameterAttributeArray, $formData);

        $this->assertEquals($expectedFormData, $result['formData']);
    }

    public function classFormDataProvider(): array
    {
        return [
            'tinyintUnsigned' => [
                'tinyint unsigned',
                [
                    'max' => 1,
                    'maxlength' => 1,
                    'required' => true,
                    'type' => 'select',
                ],
                [
                    'min' => 0,
                    'max' => 1,
                    'maxlength' => 1,
                    'type' => 'select',
                    'required' => true,
                ]
            ],
            'integer' => [
                'integer',
                [
                    'min' => 0,
                    'min2' => -2147483648,
                    'max2' => 2147483647,
                    'maxlength2' => 10,
                    'type2' => 'number',
                ],
                [
                    'min' => 0,
                    'max' => 2147483647,
                    'maxlength' => 10,
                    'type' => 'number',
                    'min2' => -2147483648,
                    'max2' => 2147483647,
                    'maxlength2' => 10,
                    'type2' => 'number',
                ]
            ]
        ];
    }

    /**
     * @dataProvider intResourceParameterInfoProvider
     */
    public function test_IntResourceParameterInfoProvider_default_return_values(string $type, array $expectedResultPieces): void
    {
        $this->parameterAttributeArray['type'] = $type;
        $result = $this->intResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);

        $this->assertEquals($this->getExpectedResult($expectedResultPieces), $result);
    }

    public function intResourceParameterInfoProvider(): array
    {
        return [
            'tinyint' => [
                'tinyint',
                [-128,127,3,'min:-128','max:127',]
            ],
            'tinyintUnsigned' => [
                'tinyint unsigned',
                [0,255,3,$this->minValidationForUnsigned,'max:255']
            ],
            'smallint' => [
                'smallint',
                [-32768,32767,5,'min:-32768','max:32767']
            ],
            'smallintUnsigned' => [
                'smallint unsigned',
                [0,65535,5,$this->minValidationForUnsigned,'max:65535']
            ],
            'mediumint' => [
                'mediumint',
                [-8388608,8388607,7,'min:-8388608','max:8388607']
            ],
            'mediumintUnsigned' => [
                'mediumint unsigned',
                [0,16777215,8,$this->minValidationForUnsigned,'max:16777215']
            ],
            'integer' => [
                'integer',
                [-2147483648,2147483647,10,$this->minValidationForInteger,$this->maxValidationForInteger]
            ],
            'integerUnsigned' => [
                'integer unsigned',
                [0,4294967295,10,$this->minValidationForUnsigned,$this->maxValidationForIntegerUnsigned]
            ],
            'bigint' => [
                'bigint',
                [-9223372036854775808,9223372036854775807,19,'min:-9223372036854775808','max:9223372036854775807']
            ],
            'bigintUnsigned' => [
                'bigint unsigned',
                [0,18446744073709551615,20,$this->minValidationForUnsigned,'max:18446744073709551615']
            ],
            'int' => [
                'int',
                [-2147483648,2147483647,10,$this->minValidationForInteger,$this->maxValidationForInteger]
            ],
            'intUnsigned' => [
                'int unsigned',
                [0,4294967295,10,$this->minValidationForUnsigned,$this->maxValidationForIntegerUnsigned]
            ],
        ];
    }

    // TODO: test required

    protected function getExpectedResult(array $expectedResultPieces): array
    {
        return [
            'apiDataType' => 'int',
            'formData' => [
                'min' => $expectedResultPieces[0],
                'max' => $expectedResultPieces[1],
                'maxlength' => $expectedResultPieces[2],
                'type' => 'number',
            ],
            'defaultValidationRules' => [
                'integer',
                $expectedResultPieces[3],
                $expectedResultPieces[4]
            ]
        ];
    }
}
