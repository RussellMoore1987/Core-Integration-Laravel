<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\IntResourceParameterInfoProvider;
use App\Models\Project;
use Tests\TestCase;

// TODO: add validation rules to test
// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (I have a stash of tests**** EndpointValidatorTest.php) (sub IntResourceParameterInfoProvider DateResourceParameterInfoProvider)
// [] read over
// [] test groups, rest, context
// [] add return type : void
// [] testing what I need to test

class IntResourceParameterInfoProviderTest extends TestCase
{
    protected $project;
    protected $intResourceParameterInfoProvider;
    protected $expectedResult;

    protected $parameterAttributeArray;
    protected $tinyintUnsignedParameterAttributeArray;

    protected function setUp(): void
    {
        parent::setUp();

        $this->intResourceParameterInfoProvider = new IntResourceParameterInfoProvider();

        $this->project = new Project();
    }

    /**
     * @dataProvider intResourceParameterInfoProvider
     */
    public function test_IntResourceParameterInfoProvider_default_return_values($type, $expectedResult)
    {
        $this->setupTestingData();
        
        $this->parameterAttributeArray['type'] = $type;
        $result = $this->intResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);

        $this->assertEquals($expectedResult, $result);
    }

    public function intResourceParameterInfoProvider()
    {
        return [
            'tinyint' => [
                'tinyint',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -128,
                        'max' => 127,
                        'maxlength' => 3,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:-128',
                        'max:127',
                    ],
                ]
            ],
            'tinyint unsigned' => [
                'tinyint unsigned',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 255,
                        'maxlength' => 3,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:255',
                    ],
                ]
            ],
            'smallint' => [
                'smallint',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -32768,
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
            ],
            'smallint unsigned' => [
                'smallint unsigned',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 65535,
                        'maxlength' => 5,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:65535',
                    ],
                ]
            ],
            'mediumint' => [
                'mediumint',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -8388608,
                        'max' => 8388607,
                        'maxlength' => 7,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:-8388608',
                        'max:8388607',
                    ],
                ]
            ],
            'mediumint unsigned' => [
                'mediumint unsigned',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 16777215,
                        'maxlength' => 8,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:16777215',
                    ],
                ]
            ],
            'integer' => [
                'integer',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -2147483648,
                        'max' => 2147483647,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:-2147483648',
                        'max:2147483647',
                    ],
                ]
            ],
            'integer unsigned' => [
                'integer unsigned',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 4294967295,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:4294967295',
                    ],
                ]
            ],
            'bigint' => [
                'bigint',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -9223372036854775808,
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
            ],
            'bigint unsigned' => [
                'bigint unsigned',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 18446744073709551615,
                        'maxlength' => 20,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:18446744073709551615',
                    ],
                ]
            ],
            'int' => [
                'int',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -2147483648,
                        'max' => 2147483647,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:-2147483648',
                        'max:2147483647',
                    ],
                ]
            ],
            'int unsigned' => [
                'int unsigned',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 4294967295,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:4294967295',
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider classFormDataProvider
     */
    public function test_IntResourceParameterInfoProvider_with_class_form_data_returned($type, $formData, $expectedResult)
    {
        $this->setupTestingData();
        
        $this->parameterAttributeArray['type'] = $type;
        $formData = [
            'fakeParameterName' => $formData,
        ];

        $result = $this->intResourceParameterInfoProvider->getData($this->parameterAttributeArray, $formData);

        $this->assertEquals($expectedResult, $result);
    }

    public function classFormDataProvider()
    {
        $this->setupTestingData();

        return [
            'tinyint' => [
                'tinyint',
                [
                    'min' => 0,
                    'max' => 1,
                    'maxlength' => 1,
                ],
                [
                    'apiDataType' => 'int',
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
            ],
            'tinyint unsigned' => [
                'tinyint unsigned',
                [
                    'max' => 1,
                    'maxlength' => 1,
                    'required' => true,
                    'type' => 'select',
                ],
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 1,
                        'maxlength' => 1,
                        'type' => 'select',
                        'required' => true,
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:255',
                    ],
                ]
            ],
            'smallint' => [
                'smallint',
                [
                    'min' => -33,
                ],
                [
                    'apiDataType' => 'int',
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
            ],
            'smallint unsigned' => [
                'smallint unsigned',
                [],
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 65535,
                        'maxlength' => 5,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:65535',
                    ],
                ]
            ],
            'mediumint' => [
                'mediumint',
                [
                    'min' => 100,
                    'max' => 8388607,
                    'minlength' => 3,
                    'maxlength' => 7,
                    'type' => 'Range',
                ],
                [
                    'apiDataType' => 'int',
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
            ],
            'mediumint unsigned' => [
                'mediumint unsigned',
                [
                    'min' => 100,
                ],
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 100,
                        'max' => 16777215,
                        'maxlength' => 8,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:16777215',
                    ],
                ]
            ],
            'integer' => [
                'integer',
                [
                    'min2' => -2147483648,
                    'max2' => 2147483647,
                    'maxlength2' => 10,
                    'type2' => 'number',
                ],
                [
                    'apiDataType' => 'int',
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
                        'min:-2147483648',
                        'max:2147483647',
                    ],
                ]
            ],
            'integer unsigned' => [
                'integer unsigned',
                [
                    'minlength' => 3,
                ],
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 4294967295,
                        'minlength' => 3,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:4294967295',
                    ],
                ]
            ],
            'bigint' => [
                'bigint',
                [
                    'min' => 0,
                    'min2' => -9223372036854775808,
                ],
                [
                    'apiDataType' => 'int',
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
            ],
            'bigint unsigned' => [
                'bigint unsigned',
                [
                    'min' => '',
                    'max' => '',
                    'maxlength' => '',
                    'type' => '',
                ],
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => '',
                        'max' => '',
                        'maxlength' => '',
                        'type' => '',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:18446744073709551615',
                    ],
                ]
            ],
            'int' => [
                'int',
                [
                    'maxlength' => 5,
                ],
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -2147483648,
                        'max' => 2147483647,
                        'maxlength' => 5,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:-2147483648',
                        'max:2147483647',
                    ],
                ]
            ],
            'int unsigned' => [
                'int unsigned',
                [
                    'min' => 12,
                    'maxlength' => 2,
                    'type' => 'text',
                ],
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 12,
                        'max' => 4294967295,
                        'maxlength' => 2,
                        'type' => 'text',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:4294967295',
                    ],
                ]
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
}
