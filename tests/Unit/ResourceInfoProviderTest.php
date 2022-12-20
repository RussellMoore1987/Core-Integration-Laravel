<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ResourceModelInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviderFactory;
use App\Models\Project;
use App\Models\WorkHistoryType;
use Tests\TestCase;

class ResourceModelInfoProviderTest extends TestCase
{
    private $resourceModelInfoProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resourceModelInfoProvider = new ResourceModelInfoProvider(new ResourceParameterInfoProviderFactory());
    }

    public function test_ResourceModelInfoProvider_functions_results_as_expected_from_project_class()
    {
        $this->createProjectClassTestInfo();
        $this->resourceModelInfoProvider->setResource($this->project);

        $this->assertEquals($this->expectedResourceInfo['primaryKeyName'], $this->resourceModelInfoProvider->getResourcePrimaryKeyName());
        $this->assertEquals($this->expectedResourceInfo['path'], $this->resourceModelInfoProvider->getResourceClassPath());
        $this->assertEquals($this->expectedResourceInfo, $this->resourceModelInfoProvider->getResourceInfo());
        unset($this->expectedResourceInfo['primaryKeyName']);
        unset($this->expectedResourceInfo['path']);
        $this->assertEquals($this->expectedResourceInfo, $this->resourceModelInfoProvider->getResourceAcceptableParameters());

    }

    protected function createProjectClassTestInfo()
    {
        $this->project = new Project();

        $this->project->formData = [
            'is_published' => [
                'min' => 0,
                'max' => 1,
                'maxlength' => 1,  
            ],
        ];

        $this->project->availableMethodCalls = [
            'pluse1_5',
            'budgetTimeTwo',
            'newTitle',
        ];

        $this->project->availableIncludes = [
            'images',
            'tags',
            'categories',
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

        $this->expectedResourceInfo = [
            'primaryKeyName' => 'id',
            'path' => 'App\Models\Project',
            'acceptableParameters' => [
                'id' => [
                    'field' => 'id',
                    'type' => 'bigint unsigned',
                    'null' => 'no',
                    'key' => 'pri',
                    'default' => null,
                    'extra' => 'auto_increment',
                    'api_data_type' => 'int',
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
                    ]
                ],
                'title' => [
                    'field' => 'title',
                    'type' => 'varchar(75)',
                    'null' => 'no',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [
                        'required' => true
                    ],
                    'defaultValidationRules' => ['required'],
                ],
                'roles' => [
                    'field' => 'roles',
                    'type' => 'varchar(50)',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
                'client' => [
                    'field' => 'client',
                    'type' => 'varchar(50)',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
                'description' => [
                    'field' => 'description',
                    'type' => 'varchar(255)',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
                'content' => [
                    'field' => 'content',
                    'type' => 'json',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'json',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
                'video_link' => [
                    'field' => 'video_link',
                    'type' => 'varchar(255)',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
                'code_link' => [
                    'field' => 'code_link',
                    'type' => 'varchar(255)',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
                'demo_link' => [
                    'field' => 'demo_link',
                    'type' => 'varchar(255)',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
                'start_date' => [
                    'field' => 'start_date',
                    'type' => 'timestamp',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1970-01-01 00:00:01',
                        'max' => '2038-01-19 03:14:07',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
                'end_date' => [
                    'field' => 'end_date',
                    'type' => 'timestamp',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1970-01-01 00:00:01',
                        'max' => '2038-01-19 03:14:07',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
                'is_published' => [
                    'field' => 'is_published',
                    'type' => 'tinyint',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0',
                    'extra' => '',
                    'api_data_type' => 'int',
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
                ],
                'created_at' => [
                    'field' => 'created_at',
                    'type' => 'timestamp',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1970-01-01 00:00:01',
                        'max' => '2038-01-19 03:14:07',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
                'updated_at' => [
                    'field' => 'updated_at',
                    'type' => 'timestamp',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1970-01-01 00:00:01',
                        'max' => '2038-01-19 03:14:07',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
                'budget' => [
                    'field' => 'budget',
                    'type' => 'decimal(8,2)',
                    'null' => 'no',
                    'key' => '',
                    'default' => '0.00',
                    'extra' => '',
                    'api_data_type' => 'float',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
            ],
            'availableMethodCalls' => [
                'pluse1_5',
                'budgetTimeTwo',
                'newTitle',
            ],
            'availableIncludes' => [
                'images',
                'tags',
                'categories',
            ],
        ];
    }

    public function test_ResourceModelInfoProvider_functions_results_as_expected_from_WorkHistoryType_class()
    {
        $this->createWorkHistoryTypeClassTestInfo();
        $this->resourceModelInfoProvider->setResource($this->workHistoryType);

        $this->assertEquals($this->expectedResourceInfo['primaryKeyName'], $this->resourceModelInfoProvider->getResourcePrimaryKeyName());
        $this->assertEquals($this->expectedResourceInfo['path'], $this->resourceModelInfoProvider->getResourceClassPath());
        $this->assertEquals($this->expectedResourceInfo, $this->resourceModelInfoProvider->getResourceInfo());
        unset($this->expectedResourceInfo['primaryKeyName']);
        unset($this->expectedResourceInfo['path']);
        $this->assertEquals($this->expectedResourceInfo, $this->resourceModelInfoProvider->getResourceAcceptableParameters());

    }

    protected function createWorkHistoryTypeClassTestInfo()
    {
        $this->workHistoryType = new WorkHistoryType();

        $this->workHistoryType->formData = [
            'work_history_type_id' => [
                'min' => 1,
                'max' => 999999,
                'maxlength' => 6,
                'type' => 'number',
            ],
        ];

        $this->workHistoryType->validationRules = [
            'modelValidation' => [
                'work_history_type_id' => [
                    'integer',
                    'min:1',
                    'max:18446744073709551615',
                ],
                'name' => [
                    'string',
                    'max:35',
                    'min:2',
                ],
                'icon' => [
                    'string',
                    'max:50',
                    'min:2',
                ],
            ],
            'createValidation' => [
                'name' => [
                    'required',
                ],
            ],
        ];

        $this->expectedResourceInfo = [
            'primaryKeyName' => 'work_history_type_id',
            'path' => 'App\Models\WorkHistoryType',
            'acceptableParameters' => [
                'work_history_type_id' => [
                    'field' => 'work_history_type_id',
                    'type' => 'bigint unsigned',
                    'null' => 'no',
                    'key' => 'pri',
                    'default' => null,
                    'extra' => 'auto_increment',
                    'api_data_type' => 'int',
                    'formData' => [
                        'min' => 1,
                        'max' => 999999,
                        'maxlength' => 6,
                        'type' => 'number',
                    ],
                    'defaultValidationRules' => [
                        'integer',
                        'min:0',
                        'max:18446744073709551615',
                    ],
                ],
                'name' => [
                    'field' => 'name',
                    'type' => 'varchar(35)',
                    'null' => 'no',
                    'key' => 'uni',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [
                        'required' => true
                    ],
                    'defaultValidationRules' => ['required'],
                ],
                'icon' => [
                    'field' => 'icon',
                    'type' => 'varchar(50)',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'string',
                    'formData' => [],
                    'defaultValidationRules' => [],
                ],
                'created_at' => [
                    'field' => 'created_at',
                    'type' => 'timestamp',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1970-01-01 00:00:01',
                        'max' => '2038-01-19 03:14:07',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
                'updated_at' => [
                    'field' => 'updated_at',
                    'type' => 'timestamp',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                    'api_data_type' => 'date',
                    'formData' => [
                        'type' => 'date',
                        'min' => '1970-01-01 00:00:01',
                        'max' => '2038-01-19 03:14:07',
                    ],
                    'defaultValidationRules' => [
                        'date',
                        'after_or_equal:1970-01-01 00:00:01',
                        'before_or_equal:2038-01-19 03:14:07',
                    ],
                ],
            ],
            'availableMethodCalls' => [],
            'availableIncludes' => [],
        ];
    }
}
