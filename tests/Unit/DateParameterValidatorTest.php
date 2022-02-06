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
    public function test_DateParameterValidator_validate_function_with_random_string_use_default_date()
    {
        $dateString = 'I am not a date';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '1970-01-01 00:00:00',
                "originalDate" => $dateString,
                "comparisonOperator" => '=',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_no_date_use_default_date()
    {
        $dateString = '';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '1970-01-01 00:00:00',
                "originalDate" => $dateString,
                "comparisonOperator" => '=',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_equal_to_with_out_action_operator()
    {
        $dateString = '2020-01-01 12:45:59';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '=',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_equal_to_by_default()
    {
        $dateString = '2020-01-01 12:45:59::sam';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '=',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_gt()
    {
        $dateString = '2020-01-01 12:45:59::GT';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '>',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_greater_than()
    {
        $dateString = '2020-01-01 12:45:59::greaterThan';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '>',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_greater_than_or_equal()
    {
        $dateString = '2020-01-01 12:45:59::greaterThanOrEqual';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '>=',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_gte()
    {
        $dateString = '2020-01-01 12:45:59::GTE';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '>=',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_less_than()
    {
        $dateString = '2020-01-01 12:45:59::lessThan';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '<',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_lt()
    {
        $dateString = '2020-01-01 12:45:59::LT';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '<',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_less_than_or_equal()
    {
        $dateString = '2020-01-01 12:45:59::lessThanOrEqual';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '<=',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_than_or_equal_to_using_lte()
    {
        $dateString = '2020-01-01 12:45:59::LTE';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperator" => '<=',
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

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
        $dateString = '1/1/2020 01:01:59,2020-12-31::bt';
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
        $dateString = '1/1/2222,2021-01-01::between';
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
        $dateString = '2021-01-01::BETWEEN';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => "2021-01-01 00:00:00",
                "originalDate" => $dateString,
                "comparisonOperator" => 'bt',
                "parameterError" => [
                    'The between date action requires two dates, It only utilizes the first two if more are passed in. ex: 2021-01-01,2021-12-31::BT.'
                ]
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getRejectedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_between_error_no_dates()
    {
        $dateString = '::BT';
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => "1970-01-01 00:00:00",
                "originalDate" => $dateString,
                "comparisonOperator" => 'bt',
                "parameterError" => [
                    'The between date action requires two dates, It only utilizes the first two if more are passed in. ex: 2021-01-01,2021-12-31::BT.'
                ]
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getRejectedParameters());
    }
}
