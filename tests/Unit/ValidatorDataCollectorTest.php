<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ValidatorDataCollector;
use App\Models\Project;
use Tests\TestCase;

class ValidatorDataCollectorTest extends TestCase
{
    private $validatorDataCollector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = new ValidatorDataCollector();
    }

    /**
     * @dataProvider setArrayParameterFunctionProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_setArrayParameter_functionality_returns_expected_results($setFunction, $getFunction): void
    {
        $this->validatorDataCollector->$setFunction(['team' => 12]);
        $this->validatorDataCollector->$setFunction([
            'start_date' => [
                'dateCoveredTo' => [2222, 2021],
                'originalDate' => '2222,2021::BT',
                'comparisonOperator' => 'bt',
            ]
        ]);

        $expectedOutput = [
            'team' => 12,
            'start_date' => [
                'dateCoveredTo' => [2222, 2021],
                'originalDate' => '2222,2021::BT',
                'comparisonOperator' => 'bt',
            ]
        ];

        $this->assertEquals($expectedOutput, $this->validatorDataCollector->$getFunction());
    }

    public function setArrayParameterFunctionProvider(): array
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
    public function test_getValidatedMetaData_function_returns_nulls_when_no_data_is_set(): void
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
    public function test_getValidatedMetaData_function_returns_expected_results_after_being_set(): void
    {
        $this->setAllValidatorDataCollectorAttributes();

        $expectedParameterOutput = [
            'team' => 12,
            'start_date' => [
                'dateCoveredTo' => [2222, 2021],
            ]
        ];

        $expectedOutput = [
            'endpointData' => ['resource' => 'projects','endpointValid' => true,],
            'resourceInfo' => ['columnData' => ['projects' => ['...']],'otherData' => true,],
            'rejectedParameters' => $expectedParameterOutput,
            'acceptedParameters' => $expectedParameterOutput,
            'queryArguments' => $expectedParameterOutput,
        ];

        $this->assertEquals($expectedOutput, $this->validatorDataCollector->getValidatedMetaData());
    }

    /**
     * @group context
     * @group allRequestMethods
     */
    public function test_reset_function_resets_the_validatorDataCollector(): void
    {
        $this->setAllValidatorDataCollectorAttributes();

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

    protected function setAllValidatorDataCollectorAttributes(): void
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
