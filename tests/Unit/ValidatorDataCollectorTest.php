<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ValidatorDataCollector;
use App\Models\Project;
use Tests\TestCase;

class ValidatorDataCollectorTest extends TestCase
{
    private $endpointData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ValidatorDataCollector = new ValidatorDataCollector();

        $this->endpointData = [
            'resource' => 'projects',
            'endpointValid' => true,
        ];

        $this->resourceInfo = [
            'columnData' => ['projects' => ['...']],
            'otherData' => true,
        ];

        $this->parameters = [
            ['team' => 12],
            [
                'start_date' => [
                    'dateCoveredTo' => [2222, 2021],
                    'originalDate' => '2222,2021::BT',
                    'comparisonOperator' => 'bt',
                ]
            ],
        ];
        $this->expectedParameters = [
            'team' => 12,
            'start_date' => [
                'dateCoveredTo' => [2222, 2021],
                'originalDate' => '2222,2021::BT',
                'comparisonOperator' => 'bt',
            ]
        ];
    }

    // tests ------------------------------------------------------------
    /**
     * @dataProvider parameterFunctions
     */
    public function test_setParameter_functions($setFunction, $getFunction)
    {
        $this->ValidatorDataCollector->$setFunction($this->parameters[0]);
        $this->ValidatorDataCollector->$setFunction($this->parameters[1]);

        $this->assertEquals($this->expectedParameters, $this->ValidatorDataCollector->$getFunction());
    }
    public function parameterFunctions()
    {
        return [
            'rejectedParameters' => ['setRejectedParameters','getRejectedParameters'],
            'acceptedParameters' => ['setAcceptedParameters','getAcceptedParameters'],
            'queryArguments' => ['setQueryArgument','getQueryArguments'],
        ];
    }

    public function test_getValidatedMetaData_function()
    {
        $this->setAllValidatedMetaDataParameters();

        $expectedOutput = [
            'endpointData' => $this->endpointData,
            'resourceInfo' => $this->resourceInfo,
            'rejectedParameters' => $this->expectedParameters,
            'acceptedParameters' => $this->expectedParameters,
            'queryArguments' => $this->expectedParameters,
        ];

        $this->assertEquals($expectedOutput, $this->ValidatorDataCollector->getValidatedMetaData());
    }

    private function setAllValidatedMetaDataParameters()
    {
        $this->ValidatorDataCollector->resource = 'projects';
        $this->ValidatorDataCollector->resourceId = '12342';
        $this->ValidatorDataCollector->parameters = ['name' => 'sam'];
        $this->ValidatorDataCollector->requestMethod = 'get';
        $this->ValidatorDataCollector->resourceObject = new Project();
        $this->ValidatorDataCollector->url = 'https://foxpest.atlassian.net/jira/software/projects/PA/boards/16';
        $this->ValidatorDataCollector->endpointData = $this->endpointData;
        $this->ValidatorDataCollector->resourceInfo = $this->resourceInfo;

        $this->ValidatorDataCollector->setRejectedParameters($this->parameters[0]);
        $this->ValidatorDataCollector->setRejectedParameters($this->parameters[1]);
        $this->ValidatorDataCollector->setAcceptedParameters($this->parameters[0]);
        $this->ValidatorDataCollector->setAcceptedParameters($this->parameters[1]);
        $this->ValidatorDataCollector->setQueryArgument($this->parameters[0]);
        $this->ValidatorDataCollector->setQueryArgument($this->parameters[1]);
    }

    public function test_getValidatedMetaData_function_with_nulls_returned()
    {
        $expectedOutput = [
            'endpointData' => [],
            'resourceInfo' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [],
            'queryArguments' => [],
        ];

        $this->assertEquals($expectedOutput, $this->ValidatorDataCollector->getValidatedMetaData());
    }

    public function test_collector_reset_function()
    {
        $this->setAllValidatedMetaDataParameters();

        $expectedOutput = [
            'endpointData' => [],
            'resourceInfo' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [],
            'queryArguments' => [],
        ];

        $this->ValidatorDataCollector->reset();

        $this->assertEquals($expectedOutput, $this->ValidatorDataCollector->getValidatedMetaData());
        $this->assertEquals(null, $this->ValidatorDataCollector->resource);
        $this->assertEquals(null, $this->ValidatorDataCollector->resourceId);
        $this->assertEquals([], $this->ValidatorDataCollector->parameters);
        $this->assertEquals(null, $this->ValidatorDataCollector->requestMethod);
        $this->assertEquals(null, $this->ValidatorDataCollector->resourceObject);
        $this->assertEquals(null, $this->ValidatorDataCollector->url);
    }
}
