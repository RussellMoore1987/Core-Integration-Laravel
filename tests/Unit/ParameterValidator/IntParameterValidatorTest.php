<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Tests\TestCase;

class IntParameterValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = new ValidatorDataCollector();
        $this->intParameterValidator = new IntParameterValidator();
    }

    // tests ------------------------------------------------------------
    public function test_IntParameterValidator_validate_function_with_random_string()
    {
        $comparisonOperator = '';
        $intString = 'I am not a int' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => $intString,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
                "parameterError" => [
                    [
                      "value" => "I am not a int",
                      "valueError" => "The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string. This parameter was not set."      
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_no_date_use_default_date()
    {
        $comparisonOperator = '';
        $intString = '' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '1970-01-01 00:00:00',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_equal_to_with_out_action_operator()
    {
        $comparisonOperator = '';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_equal_to_by_default()
    {
        $comparisonOperator = 'sam';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_or_equal_to_using_gt()
    {
        $comparisonOperator = 'GT';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_or_equal_to_using_greater_than()
    {
        $comparisonOperator = 'greaterThan';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_or_equal_to_using_greater_than_or_equal()
    {
        $comparisonOperator = 'greaterThanOrEqual';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_or_equal_to_using_gte()
    {
        $comparisonOperator = 'GTE';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_or_equal_to_using_less_than()
    {
        $comparisonOperator = 'lessThan';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_or_equal_to_using_lt()
    {
        $comparisonOperator = 'LT';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_or_equal_to_using_less_than_or_equal()
    {
        $comparisonOperator = 'lessThanOrEqual';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_lte()
    {
        $comparisonOperator = 'LTE';
        $intString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => '2020-01-01 12:45:59',
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_default_date_conversion()
    {
        $comparisonOperator = 'BT';
        $intString = 'Soso!!!,NoDateForYou::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [
                  0 => "1970-01-01 00:00:00",
                  1 => "1970-01-01 23:59:59"
                ],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_between()
    {
        $comparisonOperator = 'bt';
        $intString = '1/1/2020 01:01:59,2020-12-31::'  . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [
                  0 => "2020-01-01 01:01:59",
                  1 => "2020-12-31 23:59:59"
                ],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        // dd($this->validatorDataCollector->getAcceptedParameters(), $this->validatorDataCollector->getRejectedParameters());

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_first_date_grater_than_last_date()
    {
        $comparisonOperator = 'between';
        $intString = '1/1/2222,2021-01-01::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [
                  0 => "2222-01-01 00:00:00",
                  1 => "2021-01-01 23:59:59"
                ],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                "parameterError" => [
                    'The first date "2222-01-01 00:00:00" must be smaller than the last date "2021-01-01 23:59:59" sent in.'
                ]
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_no_second_date()
    {
        $comparisonOperator = 'BETWEEN';
        $intString = '2021-01-01::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => "2021-01-01 00:00:00",
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                "parameterError" => [
                    'The between date action requires two dates, ex: 2021-01-01,2021-12-31::BT. It only utilizes the first two if more are passed in.'
                ]
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_no_dates()
    {
        $comparisonOperator = 'BT';
        $intString = '::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => "1970-01-01 00:00:00",
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                "parameterError" => [
                    'The between date action requires two dates, ex: 2021-01-01,2021-12-31::BT. It only utilizes the first two if more are passed in.'
                ]
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_more_then_two_between_dates()
    {
        $comparisonOperator = 'BT';
        $intString = '1970-01-01,1978-01-01,1999-01-01::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [
                    "1970-01-01 00:00:00",
                    "1978-01-01 23:59:59",
                ],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }
}
