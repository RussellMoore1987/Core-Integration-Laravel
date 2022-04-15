<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ClassDataProvider;
use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use Tests\TestCase;

class ClassDataProviderTest extends TestCase
{
    private $classDataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classDataProvider = new ClassDataProvider(new DataTypeDeterminerFactory());
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
                            ],
                            'title' => [
                                'field' => 'title',
                                'type' => 'varchar(75)',
                                'null' => 'no',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                            ],
                            'roles' => [
                                'field' => 'roles',
                                'type' => 'varchar(50)',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                            ],
                            'client' => [
                                'field' => 'client',
                                'type' => 'varchar(50)',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                            ],
                            'description' => [
                                'field' => 'description',
                                'type' => 'varchar(255)',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                            ],
                            'content' => [
                                'field' => 'content',
                                'type' => 'json',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'json',
                            ],
                            'video_link' => [
                                'field' => 'video_link',
                                'type' => 'varchar(255)',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                            ],
                            'code_link' => [
                                'field' => 'code_link',
                                'type' => 'varchar(255)',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                            ],
                            'demo_link' => [
                                'field' => 'demo_link',
                                'type' => 'varchar(255)',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                            ],
                            'start_date' => [
                                'field' => 'start_date',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                            ],
                            'end_date' => [
                                'field' => 'end_date',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                            ],
                            'is_published' => [
                                'field' => 'is_published',
                                'type' => 'tinyint',
                                'null' => 'no',
                                'key' => '',
                                'default' => '0',
                                'extra' => '',
                                'api_data_type' => 'int',
                            ],
                            'created_at' => [
                                'field' => 'created_at',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                            ],
                            'updated_at' => [
                                'field' => 'updated_at',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                            ],
                            'budget' => [
                                'field' => 'budget',
                                'type' => 'decimal(8,2)',
                                'null' => 'no',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'float',
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
                              ],
                              'name' => [
                                'field' => 'name',
                                'type' => 'varchar(35)',
                                'null' => 'no',
                                'key' => 'uni',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                              ],
                              'icon' => [
                                'field' => 'icon',
                                'type' => 'varchar(50)',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'string',
                              ],
                              'created_at' => [
                                'field' => 'created_at',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
                              ],
                              'updated_at' => [
                                'field' => 'updated_at',
                                'type' => 'timestamp',
                                'null' => 'yes',
                                'key' => '',
                                'default' => null,
                                'extra' => '',
                                'api_data_type' => 'date',
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
