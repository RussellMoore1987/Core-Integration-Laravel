<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ValidatorDataCollector;
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
    public function test_setEndpointData_function()
    {
        $this->ValidatorDataCollector->setEndpointData($this->endpointData); 
        
        $this->assertEquals($this->endpointData, $this->ValidatorDataCollector->getEndpointData());
    }

    public function test_setResourceInfo_function()
    {
        $this->ValidatorDataCollector->setResourceInfo($this->resourceInfo); 
        
        $this->assertEquals($this->resourceInfo, $this->ValidatorDataCollector->getResourceInfo());
    }

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
            'rejectedParameters' => ['setRejectedParameter','getRejectedParameters'],
            'acceptedParameters' => ['setAcceptedParameter','getAcceptedParameters'],
            'queryArguments' => ['setQueryArgument','getQueryArguments'],
        ];
    }

    public function test_getValidatedMetaData_function()
    {
        $this->setAllParameters(); 

        $expectedOutput = [
            'endpointData' => $this->endpointData,
            'resourceInfo' => $this->resourceInfo,
            'rejectedParameters' => $this->expectedParameters,
            'acceptedParameters' => $this->expectedParameters,
            'queryArguments' => $this->expectedParameters,
        ];

        $this->assertEquals($expectedOutput, $this->ValidatorDataCollector->getValidatedMetaData());
    }

    private function setAllParameters()
    {
        $this->ValidatorDataCollector->setEndpointData($this->endpointData);
        $this->ValidatorDataCollector->setResourceInfo($this->resourceInfo);
        $this->ValidatorDataCollector->setRejectedParameter($this->parameters[0]); 
        $this->ValidatorDataCollector->setRejectedParameter($this->parameters[1]); 
        $this->ValidatorDataCollector->setAcceptedParameter($this->parameters[0]);  
        $this->ValidatorDataCollector->setAcceptedParameter($this->parameters[1]); 
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
        $this->setAllParameters(); 

        $expectedOutput = [
            'endpointData' => [],
            'resourceInfo' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [],
            'queryArguments' => [],
        ];

        $this->ValidatorDataCollector->reset();

        $this->assertEquals($expectedOutput, $this->ValidatorDataCollector->getValidatedMetaData());
    }
}
