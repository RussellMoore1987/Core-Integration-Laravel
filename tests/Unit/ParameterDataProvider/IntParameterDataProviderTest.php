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

        $this->createProject();
    }

    /**
     * @dataProvider intParameterDataProvider
     */
    public function test_IntParameterDataProvider_default_return_values($dataType, $parameterName, $expectedResult)
    {
        unset($this->project->formData);
        unset($this->project->validationRules);
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:-128',
                        'max:127',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:255',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:-32768',
                        'max:32767',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:65535',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:-8388608',
                        'max:8388607',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:16777215',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:-2147483648',
                        'max:2147483647',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:4294967295',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:-9223372036854775808',
                        'max:9223372036854775807',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:18446744073709551615',
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
                    'defaultValidationRules' => [
                        'integer',
                        'min:-2147483648',
                        'max:2147483647',
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
    public function test_IntParameterDataProvider_with_class_form_data_returned($dataType, $formData, $expectedResult)
    {
        $this->project->formData['fakeParameterName'] = $formData;
        $this->expectedResult = $expectedResult;

        $result = $this->intParameterDataProvider->getData($dataType, 'fakeParameterName', $this->project);

        $this->assertEquals($this->expectedResult, $result);
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

    protected function createProject()
    {
        $this->project = new Project();

        $this->project->formData = [
            'is_published' => [
                'min' => 0,
                'max' => 1,
                'maxlength' => 1,  
            ],
        ];
    
        $this->project->validationRules = [
            'modelValidation' => [
                'id' => [
                    'integer',
                    'min:1',
                    'max:18446744073709551615',
                ],
                'title' => [
                    'string',
                    'max:75',
                    'min:2',
                ],
                'roles' => [
                    'string',
                    'max:50',
                    'nullable',
                ],
                'client' => [
                    'string',
                    'max:50',
                    'nullable',
                ],
                'description' => [
                    'string',
                    'max:255',
                    'min:10',
                    'nullable',
                ],
                'content' => [
                    'string',
                    'json',
                    'nullable',
                ],
                'video_link' => [
                    'string',
                    'max:255',
                    'nullable',
                ],
                'code_link' => [
                    'string',
                    'max:255',
                    'nullable',
                ],
                'demo_link' => [
                    'string',
                    'max:255',
                    'nullable',
                ],
                'start_date' => [
                    'date',
                    'nullable',
                ],
                'end_date' => [
                    'date',
                    'nullable',
                ],
                'is_published' => [
                    'integer',
                    'min:0',
                    'max:1',
                ],
                'budget' => [
                    'numeric',
                    'max:999999.99',
                    'min:0',
                ],
            ],
            'createValidation' => [
                'title' => [
                    'required',
                ],
                'roles' => [
                    'required',
                ],
                'description' => [
                    'required',
                ],
                'start_date' => [
                    'required',
                ],
                'budget' => [
                    'required',
                ],
            ],
        ];
    }
}
