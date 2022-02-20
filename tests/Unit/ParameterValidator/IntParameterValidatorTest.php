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
            'team_id' => [
                'intCoveredTo' => $intString,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => 'I am not a int',
                      'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string. This parameter was not set.'      
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_no_int_blank_string()
    {
        $comparisonOperator = '';
        $intString = '' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            'team_id' => [
                'intCoveredTo' => $intString,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => '',
                      'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string. This parameter was not set.'      
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_equal_to_with_out_action_operator()
    {
        $comparisonOperator = '';
        $intString = '1' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            'team_id' => [
                'intCoveredTo' => 1,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_equal_to_by_default()
    {
        $comparisonOperator = 'sam';
        $intString = '1::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            'team_id' => [
                "intCoveredTo" => 1,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_greater_then_using_gt()
    {
        $comparisonOperator = 'GT';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_greater_then_using_greater_than()
    {
        $comparisonOperator = 'greaterThan';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_greater_then_or_equal_to_using_greater_than_or_equal()
    {
        $comparisonOperator = 'greaterThanOrEqual';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_greater_then_or_equal_to_using_gte()
    {
        $comparisonOperator = 'GTE';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_using_less_than()
    {
        $comparisonOperator = 'lessThan';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_using_lt()
    {
        $comparisonOperator = 'LT';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => 4,
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
        $intString = '10::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => 10,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_lte_with_two_parameter()
    {
        $comparisonOperator = 'LTE';
        $int = '10,56';
        $intString = $int . '::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            'team_id' => [
                'intCoveredTo' => $int,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => $int,
                      'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string. This parameter was not set.'      
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_between()
    {
        $comparisonOperator = 'bt';
        $intString = '1,100::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [1,100],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_first_int_grater_than_last_int()
    {
        $comparisonOperator = 'between';
        $intString = '100,1::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            'team_id' => [
                'intCoveredTo' => [100,1],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => [100,1],
                      'valueError' => 'The First int must be smaller then the second int, ex: 10,60::BT. This between action only utilizes the first two array items if more are passed in. This parameter was not set.'      
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_no_second_int()
    {
        $comparisonOperator = 'BETWEEN';
        $intString = '1::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            'team_id' => [
                'intCoveredTo' => 1,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => 1,
                      'valueError' => 'The between int action requires two ints, ex: 10,60::BT. This between action only utilizes the first two array items if more are passed in. This parameter was not set.'      
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_no_ints()
    {
        $comparisonOperator = 'BT';
        $intString = '::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            'team_id' => [
                'intCoveredTo' => '',
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => '',
                      'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string. This parameter was not set.'      
                    ],
                    [
                      'value' => '',
                      'valueError' => 'The between int action requires two ints, ex: 10,60::BT. This between action only utilizes the first two array items if more are passed in. This parameter was not set.'      
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_more_then_two_between_ints()
    {
        $comparisonOperator = 'BT';
        $intString = '1,100,33::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [1,100],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_in()
    {
        $comparisonOperator = 'in';
        $intString = '1,100,33,88,99,55::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [1,100,33,88,99,55],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'in',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_in_by_default()
    {
        $comparisonOperator = '';
        $intString = '1,100,33,88,99,55' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [1,100,33,88,99,55],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'in',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_not_in()
    {
        $comparisonOperator = 'notIn';
        $intString = '1,100,33,88,99,55::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            "team_id" => [
                "intCoveredTo" => [1,100,33,88,99,55],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'notin',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getAcceptedParameters());
    }

    public function test_IntParameterValidator_validate_function_with_in_all_array_items_are_bad()
    {
        $comparisonOperator = 'IN';
        $intString = 'sam,6.87,.01,fugue::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedReturnData = [
            'team_id' => [
                'intCoveredTo' => '',
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                        "value" => "sam",
                        "valueError" => "The value at the index of 0 is not an int. Only ints are permitted for this parameter. Your value is a string."
                    ],
                    [
                        "value" => 6.87,
                        "valueError" => "The value at the index of 1 is not an int. Only ints 
                  are permitted for this parameter. Your value is a float."
                    ],
                    [
                        "value" => 0.01,
                        "valueError" => "The value at the index of 2 is not an int. Only ints 
                  are permitted for this parameter. Your value is a float."
                    ],
                    [
                        "value" => "fugue",
                        "valueError" => "The value at the index of 3 is not an int. Only ints 
                  are permitted for this parameter. Your value is a string."
                    ],
                    [
                        "value" => "sam,6.87,.01,fugue",
                        "valueError" => "There are no ints available in this array. This parameter was not set."
                    ],
                    // ! working here ******************************************************************
                    // should not have this last one
                    [
                        "value" => "sam,6.87,.01,fugue",
                        "valueError" => "The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string. This parameter was not set."      
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        dd($this->validatorDataCollector->getRejectedParameters());

        $this->assertEquals($expectedReturnData, $this->validatorDataCollector->getRejectedParameters());
    }
    // array bad
    // array mixed
    // getAcceptedParameters on full fail, getAcceptedParameters = [], all that apply
}
