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
            'endpoint' => 'projects',
            'endpointValid' => true,
        ];

        $this->extraData = [
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

    public function test_setExtraData_function()
    {
        $this->ValidatorDataCollector->setExtraData($this->extraData); 
        
        $this->assertEquals($this->extraData, $this->ValidatorDataCollector->getExtraData());
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

    public function test_getAllData_function()
    {
        $this->setAllParameters(); 

        $expectedOutput = [
            'endpointData' => $this->endpointData,
            'extraData' => $this->extraData,
            'rejectedParameters' => $this->expectedParameters,
            'acceptedParameters' => $this->expectedParameters,
            'queryArguments' => $this->expectedParameters,
        ];

        $this->assertEquals($expectedOutput, $this->ValidatorDataCollector->getAllData());
    }

    private function setAllParameters()
    {
        $this->ValidatorDataCollector->setEndpointData($this->endpointData);
        $this->ValidatorDataCollector->setExtraData($this->extraData);
        $this->ValidatorDataCollector->setRejectedParameter($this->parameters[0]); 
        $this->ValidatorDataCollector->setRejectedParameter($this->parameters[1]); 
        $this->ValidatorDataCollector->setAcceptedParameter($this->parameters[0]);  
        $this->ValidatorDataCollector->setAcceptedParameter($this->parameters[1]); 
        $this->ValidatorDataCollector->setQueryArgument($this->parameters[0]);  
        $this->ValidatorDataCollector->setQueryArgument($this->parameters[1]); 
    }

    public function test_getAllData_function_with_nulls_returned()
    {
        $expectedOutput = [
            'endpointData' => [],
            'extraData' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [],
            'queryArguments' => [],
        ];

        $this->assertEquals($expectedOutput, $this->ValidatorDataCollector->getAllData());
    }

    public function test_collector_reset_function()
    {
        $this->setAllParameters(); 

        $expectedOutput = [
            'endpointData' => [],
            'extraData' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [],
            'queryArguments' => [],
        ];

        $this->ValidatorDataCollector->reset();

        $this->assertEquals($expectedOutput, $this->ValidatorDataCollector->getAllData());
    }
}
