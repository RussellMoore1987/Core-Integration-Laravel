<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ValidatorDataCollector;
use App\Models\Project;
use Tests\TestCase;

// ! Start here ****************************************************************** read over file and test readability, test coverage, test organization, tests grouping, go one by one (I have a stash of tests**** EndpointValidatorTest.php)
// [] read over
// [x] test groups
// [x] function output -> : void
// [] testing what I need to
// [] put groups on other tests, look for * @group

class ValidatorDataCollectorTest extends TestCase
{
    private $validatorDataCollector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = new ValidatorDataCollector();
    }

    /**
     * @dataProvider parameterFunctions
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    // TODO: better name
    public function test_validatorDataCollector_setArrayParameter_functionality_for_each_public_function_that_uses_it($setFunction, $getFunction) : void
    {
        $this->validatorDataCollector->$setFunction(['team' => 12]);
        $this->validatorDataCollector->$setFunction([
            'start_date' => [
                'dateCoveredTo' => [2222, 2021],
                'originalDate' => '2222,2021::BT',
                'comparisonOperator' => 'bt',
            ]
        ]);

        $expectedParameters = [
            'team' => 12,
            'start_date' => [
                'dateCoveredTo' => [2222, 2021],
                'originalDate' => '2222,2021::BT',
                'comparisonOperator' => 'bt',
            ]
        ];

        $this->assertEquals($expectedParameters, $this->validatorDataCollector->$getFunction());
    }

    public function parameterFunctions() : array
    {
        return [
            'rejectedParameters' => ['setRejectedParameters','getRejectedParameters'],
            'acceptedParameters' => ['setAcceptedParameters','getAcceptedParameters'],
            'queryArguments' => ['setQueryArgument','getQueryArguments'],
        ];
    }

    /**
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_getValidatedMetaData_function_with_nulls_returned() : void
    {
        $expectedOutput = [
            'endpointData' => [],
            'resourceInfo' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [],
            'queryArguments' => [],
        ];

        $this->assertEquals($expectedOutput, $this->validatorDataCollector->getValidatedMetaData());
    }

    /**
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_getValidatedMetaData_function() : void
    {
        $this->setAllValidatedMetaDataParameters();

        $expectedParameters = [
            'team' => 12,
            'start_date' => [
                'dateCoveredTo' => [2222, 2021],
            ]
        ];

        $expectedOutput = [
            'endpointData' => ['resource' => 'projects','endpointValid' => true,],
            'resourceInfo' => ['columnData' => ['projects' => ['...']],'otherData' => true,],
            'rejectedParameters' => $expectedParameters,
            'acceptedParameters' => $expectedParameters,
            'queryArguments' => $expectedParameters,
        ];

        $this->assertEquals($expectedOutput, $this->validatorDataCollector->getValidatedMetaData());
    }

    /**
     * @group context
     * @group allRequestMethods
     */
    public function test_collector_reset_function() : void
    {
        $this->setAllValidatedMetaDataParameters();

        $expectedOutput = [
            'endpointData' => [],
            'resourceInfo' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [],
            'queryArguments' => [],
        ];

        $this->validatorDataCollector->reset();

        $this->assertEquals($expectedOutput, $this->validatorDataCollector->getValidatedMetaData());
        $this->assertEquals(null, $this->validatorDataCollector->resource);
        $this->assertEquals(null, $this->validatorDataCollector->resourceId);
        $this->assertEquals([], $this->validatorDataCollector->parameters);
        $this->assertEquals(null, $this->validatorDataCollector->requestMethod);
        $this->assertEquals(null, $this->validatorDataCollector->resourceObject);
        $this->assertEquals(null, $this->validatorDataCollector->url);
    }

    protected function setAllValidatedMetaDataParameters() : void
    {
        $this->validatorDataCollector->resource = 'projects';
        $this->validatorDataCollector->resourceId = '12342';
        $this->validatorDataCollector->parameters = ['name' => 'sam'];
        $this->validatorDataCollector->requestMethod = 'get';
        $this->validatorDataCollector->resourceObject = new Project();
        $this->validatorDataCollector->url = 'https://foxpest.atlassian.net/jira/software/projects/PA/boards/16';
        $this->validatorDataCollector->endpointData = ['resource' => 'projects','endpointValid' => true,];
        $this->validatorDataCollector->resourceInfo = ['columnData' => ['projects' => ['...']],'otherData' => true,];

        $parameter1 = ['team' => 12];
        $parameter2 = ['start_date' => ['dateCoveredTo' => [2222, 2021],]];
        $this->validatorDataCollector->setRejectedParameters($parameter1);
        $this->validatorDataCollector->setRejectedParameters($parameter2);
        $this->validatorDataCollector->setAcceptedParameters($parameter1);
        $this->validatorDataCollector->setAcceptedParameters($parameter2);
        $this->validatorDataCollector->setQueryArgument($parameter1);
        $this->validatorDataCollector->setQueryArgument($parameter2);
    }
}
