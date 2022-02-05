<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ValidatorDataCollector;
use Tests\TestCase;

class ValidatorDataCollectorTest extends TestCase
{
    private $endPointData;
    private $rejectedParameters;
    private $acceptedParameters;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ValidatorDataCollector = new ValidatorDataCollector();

        $this->endPointData = [
            'endpoint' => 'projects',
            'endpointValid' => true,
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
    public function test_setEndPointData_function()
    {
        $this->ValidatorDataCollector->setEndPointData($this->endPointData); 
        
        $this->assertEquals($this->endPointData, $this->ValidatorDataCollector->getEndPointData());
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

    // TODO: Test
    // getAllData
    // resetCollector
}
