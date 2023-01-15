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
    protected $minValidationForInteger = 'min:-2147483648';
    protected $maxValidationForInteger = 'max:2147483647';
    protected $maxValidationForIntegerUnsigned = 'max:4294967295';

    protected function setUp(): void
    {
        parent::setUp();

        $this->intResourceParameterInfoProvider = new IntResourceParameterInfoProvider();
    }

    /**
     * @dataProvider classFormDataProvider
     */
    public function test_IntResourceParameterInfoProvider_with_class_form_data_returned(string $type, array $formData, array $expectedResultPieces): void
    {
        $this->setupTestingData();
        
        $this->parameterAttributeArray['type'] = $type;
        $formData = [
            'fakeParameterName' => $formData,
        ];

        $result = $this->intResourceParameterInfoProvider->getData($this->parameterAttributeArray, $formData);

        $this->assertEquals($this->getExpectedResultWithCustomFormData($expectedResultPieces), $result);
    }

    public function classFormDataProvider(): array
    {
        $this->setupTestingData();

        return [
            'tinyint' => $this->getTinyintTestData(),
            'tinyintUnsigned' => $this->getTinyintUnsignedTestData(),
            'smallint' => $this->getSmallintTestData(),
            'smallintUnsigned' => $this->getSmallintUnsignedTestData(),
            'mediumint' => $this->getMediumintTestData(),
            'mediumintUnsigned' => $this->getMediumintUnsignedTestData(),
            'integer' => $this->getIntegerTestData(),
            'integerUnsigned' => $this->getIntegerUnsignedTestData(),
            'bigint' => $this->getBigintTestData(),
            'bigintUnsigned' => $this->getBigintUnsignedTestData(),
            'int' => $this->getIntTestData(),
            'intUnsigned' => $this->getIntUnsignedTestData(),
        ];
    }

    protected function getTinyintTestData(): array
    {
        return [
            'tinyint',
            [
                'min' => 0,
                'max' => 1,
                'maxlength' => 1,
            ],
            [
                'formData' => [
                    'min' => 0,
                    'max' => 1,
                    'maxlength' => 1,
                    'type' => 'number',
                ],
                'defaultValidationRules' => [
                    'integer',
                    'min:-128',
                    'max:127',
                ],
            ]
        ];
    }

    protected function getTinyintUnsignedTestData(): array
    {
        return [
            'tinyint unsigned',
            [
                'max' => 1,
                'maxlength' => 1,
                'required' => true,
                'type' => 'select',
            ],
            [
                'formData' => [
                    'min' => 0,
                    'max' => 1,
                    'maxlength' => 1,
                    'type' => 'select',
                    'required' => true,
                ],
                'defaultValidationRules' => [
                    'integer',
                    $this->minValidationForUnsigned,
                    'max:255',
                ],
            ]
        ];
    }

    protected function getSmallintTestData(): array
    {
        return [
            'smallint',
            [
                'min' => -33,
            ],
            [
                'formData' => [
                    'min' => -33,
                    'max' => 32767,
                    'maxlength' => 5,
                    'type' => 'number',
                ],
                'defaultValidationRules' => [
                    'integer',
                    'min:-32768',
                    'max:32767',
                ],
            ]
        ];
    }

    protected function getSmallintUnsignedTestData(): array
    {
        return [
            'smallint unsigned',
            ['min' => 1],
            [
                'formData' => [
                    'min' => 1,
                    'max' => 65535,
                    'maxlength' => 5,
                    'type' => 'number',
                ],
                'defaultValidationRules' => [
                    'integer',
                    $this->minValidationForUnsigned,
                    'max:65535',
                ],
            ]
        ];
    }

    protected function getMediumintTestData(): array
    {
        return [
            'mediumint',
            [
                'min' => 100,
                'max' => 8388607,
                'minlength' => 3,
                'maxlength' => 7,
                'type' => 'Range',
            ],
            [
                'formData' => [
                    'min' => 100,
                    'max' => 8388607,
                    'minlength' => 3,
                    'maxlength' => 7,
                    'type' => 'Range',
                ],
                'defaultValidationRules' => [
                    'integer',
                    'min:-8388608',
                    'max:8388607',
                ],
            ]
        ];
    }

    protected function getMediumintUnsignedTestData(): array
    {
        return [
            'mediumint unsigned',
            [
                'min' => 100,
            ],
            [
                'formData' => [
                    'min' => 100,
                    'max' => 16777215,
                    'maxlength' => 8,
                    'type' => 'number',
                ],
                'defaultValidationRules' => [
                    'integer',
                    $this->minValidationForUnsigned,
                    'max:16777215',
                ],
            ]
        ];
    }

    protected function getIntegerTestData(): array
    {
        return [
            'integer',
            [
                'min2' => -2147483648,
                'max2' => 2147483647,
                'maxlength2' => 10,
                'type2' => 'number',
            ],
            [
                'formData' => [
                    'min' => -2147483648,
                    'max' => 2147483647,
                    'maxlength' => 10,
                    'type' => 'number',
                    'min2' => -2147483648,
                    'max2' => 2147483647,
                    'maxlength2' => 10,
                    'type2' => 'number',
                ],
                'defaultValidationRules' => [
                    'integer',
                    $this->minValidationForInteger,
                    $this->maxValidationForInteger,
                ],
            ]
        ];
    }

    protected function getIntegerUnsignedTestData(): array
    {
        return [
            'integer unsigned',
            [
                'minlength' => 3,
            ],
            [
                'formData' => [
                    'min' => 0,
                    'max' => 4294967295,
                    'minlength' => 3,
                    'maxlength' => 10,
                    'type' => 'number',
                ],
                'defaultValidationRules' => [
                    'integer',
                    $this->minValidationForUnsigned,
                    $this->maxValidationForIntegerUnsigned,
                ],
            ]
        ];
    }

    protected function getBigintTestData(): array
    {
        return [
            'bigint',
            [
                'min' => 0,
                'min2' => -9223372036854775808,
            ],
            [
                'formData' => [
                    'min' => 0,
                    'min2' => -9223372036854775808,
                    'max' => 9223372036854775807,
                    'maxlength' => 19,
                    'type' => 'number',
                ],
                'defaultValidationRules' => [
                    'integer',
                    'min:-9223372036854775808',
                    'max:9223372036854775807',
                ],
            ]
        ];
    }

    protected function getBigintUnsignedTestData(): array
    {
        return [
            'bigint unsigned',
            [
                'min' => '',
                'max' => '',
                'maxlength' => '',
                'type' => '',
            ],
            [
                'formData' => [
                    'min' => '',
                    'max' => '',
                    'maxlength' => '',
                    'type' => '',
                ],
                'defaultValidationRules' => [
                    'integer',
                    $this->minValidationForUnsigned,
                    'max:18446744073709551615',
                ],
            ]
        ];
    }

    protected function getIntTestData(): array
    {
        return [
            'int',
            [
                'maxlength' => 5,
            ],
            [
                'formData' => [
                    'min' => -2147483648,
                    'max' => 2147483647,
                    'maxlength' => 5,
                    'type' => 'number',
                ],
                'defaultValidationRules' => [
                    'integer',
                    $this->minValidationForInteger,
                    $this->maxValidationForInteger,
                ],
            ]
        ];
    }

    protected function getIntUnsignedTestData(): array
    {
        return [
            'int unsigned',
            [
                'min' => 12,
                'maxlength' => 2,
                'type' => 'text',
            ],
            [
                'formData' => [
                    'min' => 12,
                    'max' => 4294967295,
                    'maxlength' => 2,
                    'type' => 'text',
                ],
                'defaultValidationRules' => [
                    'integer',
                    $this->minValidationForUnsigned,
                    $this->maxValidationForIntegerUnsigned,
                ],
            ]
        ];
    }

    protected function getExpectedResultWithCustomFormData(array $expectedResultPieces): array
    {
        return [
            'apiDataType' => 'int',
            'formData' => $expectedResultPieces['formData'],
            'defaultValidationRules' => $expectedResultPieces['defaultValidationRules']
        ];
    }

    /**
     * @dataProvider intResourceParameterInfoProvider
     */
    public function test_IntResourceParameterInfoProvider_default_return_values(string $type, array $expectedResultPieces): void
    {
        $this->setupTestingData();
        
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

    protected function setupTestingData(): void
    {
        $this->parameterAttributeArray = [
            'field' => 'fakeParameterName',
            'type' => 'tinyint',
            'null' => 'no',
            'key' => '',
            'default' => '0',
            'extra' => '',
        ];
    }

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
