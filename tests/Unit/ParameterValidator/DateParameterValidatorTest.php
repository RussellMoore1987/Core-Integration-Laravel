<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Tests\TestCase;

class DateParameterValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->ValidatorDataCollector = new ValidatorDataCollector();
        $this->DateParameterValidator = new DateParameterValidator();
    }

    // tests ------------------------------------------------------------
    public function test_DateParameterValidator_validate_function_with_random_string_use_default_date()
    {
        $comparisonOperator = '';
        $dateString = 'I am not a date' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '1970-01-01 00:00:00',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_no_date_use_default_date()
    {
        $comparisonOperator = '';
        $dateString = '' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '1970-01-01 00:00:00',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_equal_to_with_out_action_operator()
    {
        $comparisonOperator = '';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_equal_to_by_default()
    {
        $comparisonOperator = 'sam';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_gt()
    {
        $comparisonOperator = 'GT';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_greater_than()
    {
        $comparisonOperator = 'greaterThan';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_greater_than_or_equal()
    {
        $comparisonOperator = 'greaterThanOrEqual';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_gte()
    {
        $comparisonOperator = 'GTE';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_less_than()
    {
        $comparisonOperator = 'lessThan';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_lt()
    {
        $comparisonOperator = 'LT';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_less_than_or_equal()
    {
        $comparisonOperator = 'lessThanOrEqual';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_less_than_or_equal_to_using_lte()
    {
        $comparisonOperator = 'LTE';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => '2020-01-01 12:45:59',
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_default_date_conversion()
    {
        $comparisonOperator = 'BT';
        $dateString = 'Soso!!!,NoDateForYou::' . $comparisonOperator;
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
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_between()
    {
        $comparisonOperator = 'bt';
        $dateString = '1/1/2020 01:01:59,2020-12-31::'  . $comparisonOperator;
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
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        // dd($this->ValidatorDataCollector->getAcceptedParameters(), $this->ValidatorDataCollector->getRejectedParameters());

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_between_error_first_date_grater_than_last_date()
    {
        $comparisonOperator = 'between';
        $dateString = '1/1/2222,2021-01-01::' . $comparisonOperator;
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
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
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
        $comparisonOperator = 'BETWEEN';
        $dateString = '2021-01-01::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => "2021-01-01 00:00:00",
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                "parameterError" => [
                    'The between date action requires two dates, ex: 2021-01-01,2021-12-31::BT. It only utilizes the first two if more are passed in.'
                ]
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getRejectedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_between_error_no_dates()
    {
        $comparisonOperator = 'BT';
        $dateString = '::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => "1970-01-01 00:00:00",
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                "parameterError" => [
                    'The between date action requires two dates, ex: 2021-01-01,2021-12-31::BT. It only utilizes the first two if more are passed in.'
                ]
            ]
        ];

        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getRejectedParameters());
    }

    public function test_DateParameterValidator_validate_function_with_more_then_two_between_dates()
    {
        $comparisonOperator = 'BT';
        $dateString = '1970-01-01,1978-01-01,1999-01-01::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedReturnData = [
            "start_date" => [
                "dateCoveredTo" => [
                    "1970-01-01 00:00:00",
                    "1978-01-01 23:59:59",
                ],
                "originalDate" => $dateString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        
        $this->ValidatorDataCollector = $this->DateParameterValidator->validate($this->ValidatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->ValidatorDataCollector->getAcceptedParameters());
    }
}
