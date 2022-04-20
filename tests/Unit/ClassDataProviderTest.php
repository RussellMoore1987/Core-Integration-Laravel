<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ClassDataProvider;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviderFactory;
use Tests\TestCase;

class ClassDataProviderTest extends TestCase
{
    private $classDataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classDataProvider = new ClassDataProvider(new ParameterDataProviderFactory());
    }

    public function test_classDataProvider_setClass_function_for_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectErrorMessage('Class does not exist or is not a subclass of the Model class');

        $this->classDataProvider->setClass('NotAClass');
    }

    /**
     * @dataProvider classDataProvider
     */
    public function test_classDataProvider_functions_results_as_expected_from_data_provider($expectedClassInfo)
    {
        $this->classDataProvider->setClass($expectedClassInfo['path']);

        $this->assertEquals($expectedClassInfo['primaryKeyName'], $this->classDataProvider->getClassPrimaryKeyName());
        $this->assertEquals($expectedClassInfo['path'], $this->classDataProvider->getClassPath());
        $this->assertEquals($expectedClassInfo['classParameterOptions'], $this->classDataProvider->getClassAcceptableParameters());
        $this->assertEquals($expectedClassInfo, $this->classDataProvider->getClassInfo());

    }

    public function classDataProvider()
    {

        return [
            'App\Models\Project' => [
                [
                    'primaryKeyName' => 'id',
                    'path' => 'App\Models\Project',
                    'classParameterOptions' => [
                        'acceptableParameters' => [
                            'id' => [
                                'field' => 'id',
                                'type' => 'bigint unsigned',
                                'null' => 'no',
                                'key' => 'pri',
                                'default' => null,
                                'extra' => 'auto_increment',
                                'api_data_type' => 'int',
                                'formData' => [],
                            ],
                            'title' => [
                                'field' => 'title',
                                'type' => 'varchar(75)',
                                'null' => 'no',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                                'formData' => [],
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
                            ],
                            'start_date' => [
                                'field' => 'start_date',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                                'formData' => [],
                            ],
                            'end_date' => [
                                'field' => 'end_date',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                                'formData' => [],
                            ],
                            'is_published' => [
                                'field' => 'is_published',
                                'type' => 'tinyint',
                                'null' => 'no',
                                'key' => '',
                                'default' => '0',
                                'extra' => '',
                                'api_data_type' => 'int',
                                'formData' => [],
                            ],
                            'created_at' => [
                                'field' => 'created_at',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                                'formData' => [],
                            ],
                            'updated_at' => [
                                'field' => 'updated_at',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                                'formData' => [],
                            ],
                            'budget' => [
                                'field' => 'budget',
                                'type' => 'decimal(8,2)',
                                'null' => 'no',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'float',
                                'formData' => [],
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
                    ],
                ], 
            ],
            'App\Models\WorkHistoryType' => [
                [
                    'primaryKeyName' => 'work_history_type_id',
                    'path' => 'App\Models\WorkHistoryType',
                    'classParameterOptions' => [
                        'acceptableParameters' => [
                            'work_history_type_id' => [
                                'field' => 'work_history_type_id',
                                'type' => 'bigint unsigned',
                                'null' => 'no',
                                'key' => 'pri',
                                'default' => null,
                                'extra' => 'auto_increment',
                                'api_data_type' => 'int',
                                'formData' => [],
                              ],
                              'name' => [
                                'field' => 'name',
                                'type' => 'varchar(35)',
                                'null' => 'no',
                                'key' => 'uni',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                                'formData' => [],
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
                              ],
                              'created_at' => [
                                'field' => 'created_at',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                                'formData' => [],
                              ],
                              'updated_at' => [
                                'field' => 'updated_at',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                                'formData' => [],
                              ],
                        ],
                        'availableMethodCalls' => [],
                        'availableIncludes' => [],
                    ],
                ], 
            ],
        ];
    }
}
