<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Tests\TestCase;

class DateParameterValidatorTest extends TestCase
{
    private $endpointData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ValidatorDataCollector = new ValidatorDataCollector();
        $this->DateParameterValidator = new DateParameterValidator();
    }

    // tests ------------------------------------------------------------
    // bt not all dates
    public function test_DateParameterValidator_validate_function_with_default_date_conversion()
    {
        $dateString = 'Soso!!!,NoDateForYou::BT';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => [
                  0 => "1970-01-01 00:00:00",
                  1 => "1970-01-01 23:59:59"
                ],
                "originalDate" => $dateString,
                "comparisonOperator" => 'bt',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_between()
    {
        $dateString = '1/1/2020 01:01:59,2020-12-31::BT';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => [
                  0 => "2020-01-01 01:01:59",
                  1 => "2020-12-31 23:59:59"
                ],
                "originalDate" => $dateString,
                "comparisonOperator" => 'bt',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        // dd($this->ValidatorDataCollector->getAcceptedParameters(), $this->ValidatorDataCollector->getRejectedParameters());

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_between_error_first_date_grater_than_last_date()
    {
        $dateString = '1/1/2222,2021-01-01::BT';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => [
                  0 => "2222-01-01 00:00:00",
                  1 => "2021-01-01 23:59:59"
                ],
                "originalDate" => $dateString,
                "comparisonOperator" => 'bt',
                "parameterError" => [
                    'The first date "2222-01-01 00:00:00" must be smaller than the last date "2021-01-01 23:59:59" sent in.'
                ]
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getRejectedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_between_error_no_second_date()
    {
        $dateString = '2021-01-01::BT';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => "2021-01-01 00:00:00",
                "originalDate" => $dateString,
                "comparisonOperator" => 'bt',
                "parameterError" => [
                    'The between date action requires two dates, no more, no less. ex: 2021-01-01,2021-12-31::BT.'
                ]
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getRejectedParameters());
    }
}
