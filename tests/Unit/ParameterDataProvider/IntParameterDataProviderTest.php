<?php

namespace Tests\Unit\ParameterDataProvider;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\IntParameterDataProvider;
use App\Models\Project;
use Tests\TestCase;

class IntParameterDataProviderTest extends TestCase
{
    protected $project;
    protected $intParameterDataProvider;
    protected $expectedResult;

    protected function setUp(): void
    {
        parent::setUp();

        $this->intParameterDataProvider = new IntParameterDataProvider();

        $this->project = new Project();
    }

    /**
     * @dataProvider intParameterDataProvider
     */
    public function test_IntParameterDataProvider_default_return_values($parameterDataInfo, $expectedResult)
    {
        unset($this->project->formData);
        unset($this->project->validationRules);
        $result = $this->intParameterDataProvider->getData($parameterDataInfo, $this->project);

        $this->assertEquals($expectedResult, $result);
    }

    public function intParameterDataProvider()
    {
        return [
            'tinyint' => [
                [
                    'field' => 'fakeParameterName',
                    'type' => 'tinyint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'tinyint unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'smallint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'smallint unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'mediumint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'mediumint unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'integer',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'integer unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'bigint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'bigint unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'int',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'int unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
    public function test_IntParameterDataProvider_with_class_form_data_returned($parameterDataInfo, $formData, $expectedResult)
    {
        $this->project->formData = [
            'fakeParameterName' => $formData,
        ];

        $this->expectedResult = $expectedResult;

        $result = $this->intParameterDataProvider->getData($parameterDataInfo, $this->project);

        $this->assertEquals($this->expectedResult, $result);
    }

    public function classFormDataProvider()
    {
        return [
            'tinyint' => [
                [
                    'field' => 'fakeParameterName',
                    'type' => 'tinyint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'tinyint unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'smallint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'smallint unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'mediumint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'mediumint unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'integer',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'integer unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'bigint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'bigint unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'int',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
                [
                    'field' => 'fakeParameterName',
                    'type' => 'int unsigned',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                ],
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
}
