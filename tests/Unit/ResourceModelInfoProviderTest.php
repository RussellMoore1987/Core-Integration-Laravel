<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ResourceModelInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviderFactory;
use App\Models\Category;
use App\Models\WorkHistoryType;
use Tests\TestCase;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (I have a stash of tests**** EndpointValidatorTest.php) (sub ResourceParameterInfoProviderFactory)
// [x] read over
// [x] test groups, rest, context
// [x] add return type : void
// [x] testing what I need to test

class ResourceModelInfoProviderTest extends TestCase
{
    private $resourceModelInfoProvider;
    private $expectedResourceInfo;
    private $category;
    private $workHistoryType;

    protected function setUp() : void
    {
        parent::setUp();

        $this->resourceModelInfoProvider = new ResourceModelInfoProvider(new ResourceParameterInfoProviderFactory());
    }

    /**
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_getResourceInfo_gets_expected_results_from_category_class() : void
    {
        $this->createCategoryClassTestInfo();

        $actualResourceInfo = $this->resourceModelInfoProvider->getResourceInfo($this->category);

        // just asserting structure, details tested in ResourceParameterInfoProviders
        foreach ($actualResourceInfo['acceptableParameters'] as $parameterName => $parameterAttributeArray) {
            $this->assertArrayHasKey('apiDataType', $parameterAttributeArray);
            $this->assertArrayHasKey('defaultValidationRules', $parameterAttributeArray);
            $this->assertArrayHasKey('formData', $parameterAttributeArray);
            // removing these resource info items so we can assess the details of just the other resource info items
            unset($actualResourceInfo['acceptableParameters'][$parameterName]['apiDataType']);
            unset($actualResourceInfo['acceptableParameters'][$parameterName]['defaultValidationRules']);
            unset($actualResourceInfo['acceptableParameters'][$parameterName]['formData']);
        }

        $this->assertEquals($this->expectedResourceInfo, $actualResourceInfo);
    }

    protected function createCategoryClassTestInfo() : void
    {
        $this->category = new Category();

        $this->category->availableMethodCalls = [
            'pluse1_5',
            'budgetTimeTwo',
            'newTitle',
        ];

        $this->category->availableIncludes = [
            'images',
            'tags',
            'categories',
        ];

        $this->setUpExpectedResourceInfo();
    }

    protected function setUpExpectedResourceInfo() : void
    {
        $this->expectedResourceInfo = [
            'primaryKeyName' => 'id',
            'path' => 'App\Models\Category',
            'acceptableParameters' => [
                'id' => [
                  'field' => 'id',
                  'type' => 'bigint unsigned',
                  'null' => 'no',
                  'key' => 'pri',
                  'default' => null,
                  'extra' => 'auto_increment',
                ],
                'name' => [
                  'field' => 'name',
                  'type' => 'varchar(35)',
                  'null' => 'no',
                  'key' => 'uni',
                  'default' => null,
                  'extra' => '',
                ],
                'created_at' => [
                  'field' => 'created_at',
                  'type' => 'timestamp',
                  'null' => 'yes',
                  'key' => '',
                  'default' => null,
                  'extra' => '',
                ],
                'updated_at' => [
                  'field' => 'updated_at',
                  'type' => 'timestamp',
                  'null' => 'yes',
                  'key' => '',
                  'default' => null,
                  'extra' => '',
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

    /**
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_getResourceInfo_gives_expected_results_from_WorkHistoryType_class_different_primaryKeyName() : void
    {
        $this->createWorkHistoryTypeClassTestInfo();

        $actualResourceInfo = $this->resourceModelInfoProvider->getResourceInfo($this->workHistoryType);

        // just asserting structure, details tested in ResourceParameterInfoProviders
        foreach ($actualResourceInfo['acceptableParameters'] as $parameterName => $parameterAttributeArray) {
            $this->assertArrayHasKey('apiDataType', $parameterAttributeArray);
            $this->assertArrayHasKey('defaultValidationRules', $parameterAttributeArray);
            $this->assertArrayHasKey('formData', $parameterAttributeArray);
            // removing these resource info items so we can assess the details of just the other resource info items
            unset($actualResourceInfo['acceptableParameters'][$parameterName]['apiDataType']);
            unset($actualResourceInfo['acceptableParameters'][$parameterName]['defaultValidationRules']);
            unset($actualResourceInfo['acceptableParameters'][$parameterName]['formData']);
        }

        $this->assertEquals($this->expectedResourceInfo, $actualResourceInfo);
    }

    protected function createWorkHistoryTypeClassTestInfo() : void
    {
        $this->workHistoryType = new WorkHistoryType();

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
                ],
                'name' => [
                    'field' => 'name',
                    'type' => 'varchar(35)',
                    'null' => 'no',
                    'key' => 'uni',
                    'default' => null,
                    'extra' => '',
                ],
                'icon' => [
                    'field' => 'icon',
                    'type' => 'varchar(50)',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                ],
                'created_at' => [
                    'field' => 'created_at',
                    'type' => 'timestamp',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                ],
                'updated_at' => [
                    'field' => 'updated_at',
                    'type' => 'timestamp',
                    'null' => 'yes',
                    'key' => '',
                    'default' => null,
                    'extra' => '',
                ],
            ],
            'availableMethodCalls' => [],
            'availableIncludes' => [],
        ];
    }
}
