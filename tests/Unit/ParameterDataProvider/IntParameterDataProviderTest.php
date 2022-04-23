<?php

namespace Tests\Unit\ParameterDataProvider;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviders\IntParameterDataProvider;
use App\Models\Project;
use Tests\TestCase;

class IntParameterDataProviderTest extends TestCase
{
    protected $project;
    protected $intParameterDataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->intParameterDataProvider = new IntParameterDataProvider();

        $this->project = new Project();
    }

    /**
     * @dataProvider intParameterDataProvider
     */
    public function test_IntParameterDataProvider_default_return_values($dataType, $parameterName, $expectedResult)
    {
        $result = $this->intParameterDataProvider->getData($dataType, $parameterName, $this->project);

        $this->assertEquals($expectedResult, $result);
    }

    public function intParameterDataProvider()
    {
        return [
            'tinyint' => [
                'tinyint',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -128,
                        'max' => 127,
                        'maxlength' => 3,
                        'type' => 'number',
                    ],
                ]
            ],
            'tinyint unsigned' => [
                'tinyint unsigned',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 255,
                        'maxlength' => 3,
                        'type' => 'number',
                    ],
                ]
            ],
            'smallint' => [
                'smallint',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -32768,
                        'max' => 32767,
                        'maxlength' => 5,
                        'type' => 'number',
                    ],
                ]
            ],
            'smallint unsigned' => [
                'smallint unsigned',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 65535,
                        'maxlength' => 5,
                        'type' => 'number',
                    ],
                ]
            ],
            'mediumint' => [
                'mediumint',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -8388608,
                        'max' => 8388607,
                        'maxlength' => 7,
                        'type' => 'number',
                    ],
                ]
            ],
            'mediumint unsigned' => [
                'mediumint unsigned',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 16777215,
                        'maxlength' => 8,
                        'type' => 'number',
                    ],
                ]
            ],
            'integer' => [
                'integer',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -2147483648,
                        'max' => 2147483647,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                ]
            ],
            'integer unsigned' => [
                'integer unsigned',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 4294967295,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                ]
            ],
            'bigint' => [
                'bigint',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -9223372036854775808,
                        'max' => 9223372036854775807,
                        'maxlength' => 19,
                        'type' => 'number',
                    ],
                ]
            ],
            'bigint unsigned' => [
                'bigint unsigned',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 18446744073709551615,
                        'maxlength' => 20,
                        'type' => 'number',
                    ],
                ]
            ],
            'int' => [
                'int',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => -2147483648,
                        'max' => 2147483647,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                ]
            ],
            'int unsigned' => [
                'int unsigned',
                'fakeParameterName',
                [
                    'apiDataType' => 'int',
                    'formData' => [
                        'min' => 0,
                        'max' => 4294967295,
                        'maxlength' => 10,
                        'type' => 'number',
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider classFormDataProvider
     */
    public function test_IntParameterDataProvider_with_class_form_data_returned($dataType, $formData, $expectedResult)
    {
        $this->project->formData['fakeParameterName'] = $formData;

        $result = $this->intParameterDataProvider->getData($dataType, 'fakeParameterName', $this->project);

        $this->assertEquals($expectedResult, $result);
    }

    public function classFormDataProvider()
    {
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
                ]
            ],
        ];
    }
}
