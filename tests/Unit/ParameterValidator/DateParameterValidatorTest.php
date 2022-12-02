<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\validatorDataCollector;
use Tests\TestCase;

class DateParameterValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = new validatorDataCollector();
        $this->DateParameterValidator = new DateParameterValidator();
    }

    public function test_DateParameterValidator_validate_function_with_random_string_use_default_date()
    {
        $comparisonOperator = '';
        $dateString = 'I am not a date' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '1970-01-01 00:00:00',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '1970-01-01 00:00:00',
                'comparisonOperator' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_no_date_use_default_date()
    {
        $comparisonOperator = '';
        $dateString = '' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '1970-01-01 00:00:00',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '1970-01-01 00:00:00',
                'comparisonOperator' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_equal_to_with_out_action_operator()
    {
        $comparisonOperator = '';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_equal_to_by_default()
    {
        $comparisonOperator = 'sam';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_greater_then_using_gt()
    {
        $comparisonOperator = 'GT';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_greater_then_using_greater_than()
    {
        $comparisonOperator = 'greaterThan';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_greater_then_using_greater_than_symbol()
    {
        $comparisonOperator = '>';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_greater_then_or_equal_to_using_greater_than_or_equal()
    {
        $comparisonOperator = 'greaterThanOrEqual';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_greater_then_or_equal_to_using_greater_than_or_equal_symbol()
    {
        $comparisonOperator = '>=';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_greater_then_or_equal_to_using_gte()
    {
        $comparisonOperator = 'GTE';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_using_less_than()
    {
        $comparisonOperator = 'lessThan';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_using_less_than_symbol()
    {
        $comparisonOperator = '<';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_using_lt()
    {
        $comparisonOperator = 'LT';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_less_than_or_equal()
    {
        $comparisonOperator = 'lessThanOrEqual';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_less_then_or_equal_to_using_less_than_or_equal_symbol()
    {
        $comparisonOperator = '<=';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_less_than_or_equal_to_using_lte()
    {
        $comparisonOperator = 'LTE';
        $dateString = '2020-01-01 12:45:59::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2020-01-01 12:45:59',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => '2020-01-01 12:45:59',
                'comparisonOperator' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_default_date_conversion()
    {
        $comparisonOperator = 'BT';
        $dateString = 'Soso!!!,NoDateForYou::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => [
                  '1970-01-01 00:00:00',
                  '1970-01-01 23:59:59',
                ],
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => [
                    '1970-01-01 00:00:00',
                    '1970-01-01 23:59:59',
                ],
                'comparisonOperator' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_between()
    {
        $comparisonOperator = 'bt';
        $dateString = '1/1/2020 01:01:59,2020-12-31::'  . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => [
                  '2020-01-01 01:01:59',
                  '2020-12-31 23:59:59'
                ],
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => [
                    '2020-01-01 01:01:59',
                    '2020-12-31 23:59:59',
                ],
                'comparisonOperator' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_between_error_first_date_grater_than_last_date()
    {
        $comparisonOperator = 'between';
        $dateString = '1/1/2222,2021-01-01::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedRejectedParameters = [
            'start_date' => [
                'dateCoveredTo' => [
                  '2222-01-01 00:00:00',
                  '2021-01-01 23:59:59'
                ],
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    'The first date "2222-01-01 00:00:00" must be smaller than the last date "2021-01-01 23:59:59" sent in.'
                ]
            ]
        ];

        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_between_error_no_second_date()
    {
        $comparisonOperator = 'BETWEEN';
        $dateString = '2021-01-01::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedRejectedParameters = [
            'start_date' => [
                'dateCoveredTo' => '2021-01-01 00:00:00',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    'The between date action requires two dates, ex: 2021-01-01,2021-12-31::BT. It only utilizes the first two if more are passed in.'
                ]
            ]
        ];

        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_between_error_no_dates()
    {
        $comparisonOperator = 'BT';
        $dateString = '::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedRejectedParameters = [
            'start_date' => [
                'dateCoveredTo' => '1970-01-01 00:00:00',
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    'The between date action requires two dates, ex: 2021-01-01,2021-12-31::BT. It only utilizes the first two if more are passed in.'
                ]
            ]
        ];

        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_with_more_then_two_between_dates()
    {
        $comparisonOperator = 'BT';
        $dateString = '1970-01-01,1978-01-01,1999-01-01::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => [
                    '1970-01-01 00:00:00',
                    '1978-01-01 23:59:59',
                ],
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => [
                    '1970-01-01 00:00:00',
                    '1978-01-01 23:59:59',
                ],
                'comparisonOperator' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_DateParameterValidator_validate_function_function_get_all_data()
    {
        $comparisonOperator = 'BT';
        $dateString = '1970-01-01,1978-01-01,1999-01-01::' . $comparisonOperator;
        $parameterData = [
            'start_date' => $dateString
        ];

        $expectedAcceptedParameters = [
            'start_date' => [
                'dateCoveredTo' => [
                    '1970-01-01 00:00:00',
                    '1978-01-01 23:59:59',
                ],
                'originalDate' => $dateString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'start_date' => [
                'dataType' => 'date',
                'columnName' => 'start_date',
                'date' => [
                    '1970-01-01 00:00:00',
                    '1978-01-01 23:59:59',
                ],
                'comparisonOperator' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedGetAllData = [
            'endpointData' => [],
            'resourceInfo' => [],
            'acceptedParameters' => $expectedAcceptedParameters,
            'rejectedParameters' => [],
            'queryArguments' => $expectedQueryArguments,
        ];
        
        $this->validatorDataCollector = $this->DateParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedGetAllData, $this->validatorDataCollector->getAllData());
    }
}
