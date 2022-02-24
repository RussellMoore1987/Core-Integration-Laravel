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

    public function test_IntParameterValidator_validate_function_with_random_string()
    {
        $comparisonOperator = '';
        $intString = 'I am not a int' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
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

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_no_int_blank_string()
    {
        $comparisonOperator = '';
        $intString = '' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
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

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_equal_to_with_out_action_operator()
    {
        $comparisonOperator = '';
        $intString = '1' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 1,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 1,
                "comparisonOperator" => "=",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_equal_to_by_default()
    {
        $comparisonOperator = 'sam';
        $intString = '1::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                "intCoveredTo" => 1,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 1,
                "comparisonOperator" => "=",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_greater_then_using_gt()
    {
        $comparisonOperator = 'GT';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 4,
                "comparisonOperator" => ">",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_greater_then_using_greater_than()
    {
        $comparisonOperator = 'greaterThan';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 4,
                "comparisonOperator" => ">",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_greater_then_or_equal_to_using_greater_than_or_equal()
    {
        $comparisonOperator = 'greaterThanOrEqual';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 4,
                "comparisonOperator" => ">=",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_greater_then_or_equal_to_using_gte()
    {
        $comparisonOperator = 'GTE';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 4,
                "comparisonOperator" => ">=",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_using_less_than()
    {
        $comparisonOperator = 'lessThan';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 4,
                "comparisonOperator" => "<",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_less_then_using_lt()
    {
        $comparisonOperator = 'lt';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => 4,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 4,
                "comparisonOperator" => "<",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_less_than_or_equal()
    {
        $comparisonOperator = 'lessThanOrEqual';
        $intString = '10::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => 10,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 10,
                "comparisonOperator" => "<=",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_lte()
    {
        $comparisonOperator = 'LTE';
        $intString = '10::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => 10,
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => 10,
                "comparisonOperator" => "<=",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_action_that_is_not_in_notin_or_bt()
    {
        // arrays or int strings that have a comma are only used in these actions IN, NotIn, BT, or Between
        $comparisonOperator = 'LTE';
        $int = '10,56';
        $intString = $int . '::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
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
        
        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_between()
    {
        $comparisonOperator = 'bt';
        $intString = '1,100::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => [1,100],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => [1,100],
                "comparisonOperator" => "bt",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_first_int_grater_than_last_int()
    {
        $comparisonOperator = 'between';
        $intString = '100,1::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
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

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_no_second_int()
    {
        $comparisonOperator = 'BETWEEN';
        $intString = '1::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
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

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_between_error_no_ints()
    {
        $comparisonOperator = 'BT';
        $intString = '::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
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

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_between_more_then_two_ints()
    {
        $comparisonOperator = 'BT';
        $intString = '1,100,33::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => [1,100],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => [1,100],
                "comparisonOperator" => "bt",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_in()
    {
        $comparisonOperator = 'in';
        $intString = '1,100,33,88,99,55::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => [1,100,33,88,99,55],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'in',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => [1,100,33,88,99,55],
                "comparisonOperator" => "in",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_in_by_default()
    {
        $comparisonOperator = '';
        $intString = '1,100,33,88,99,55' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => [1,100,33,88,99,55],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'in',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => [1,100,33,88,99,55],
                "comparisonOperator" => "in",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_with_not_in()
    {
        $comparisonOperator = 'notIn';
        $intString = '1,100,33,88,99,55::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => [1,100,33,88,99,55],
                "originalIntString" => $intString,
                "comparisonOperatorCoveredTo" => 'notin',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => [1,100,33,88,99,55],
                "comparisonOperator" => "notin",
                "originalComparisonOperator" => $comparisonOperator,
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_where_all_array_items_are_bad()
    {
        $comparisonOperator = 'IN';
        $int = 'sam,6.87,.01,fugue';
        $intString = $int . '::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
            'team_id' => [
                'intCoveredTo' => $int,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'in',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                        "value" => "sam",
                        "valueError" => "The value at the index of 0 is not an int. Only ints are permitted for this parameter. Your value is a string."
                    ],
                    [
                        "value" => 6.87,
                        "valueError" => "The value at the index of 1 is not an int. Only ints are permitted for this parameter. Your value is a float."
                    ],
                    [
                        "value" => 0.01,
                        "valueError" => "The value at the index of 2 is not an int. Only ints are permitted for this parameter. Your value is a float."
                    ],
                    [
                        "value" => "fugue",
                        "valueError" => "The value at the index of 3 is not an int. Only ints are permitted for this parameter. Your value is a string."
                    ],
                    [
                        "value" => "sam,6.87,.01,fugue",
                        "valueError" => "There are no ints available in this array. This parameter was not set."
                    ],
                ],
            ],    
        ];

        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_where_we_have_a_mixed_array_of_good_and_bad_items()
    {
        $comparisonOperator = 'IN';
        $int = '13,6.87,6,fugue';
        $intString = $int . '::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
            'team_id' => [
                'intCoveredTo' => [13,6],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'in',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                        "value" => 6.87,
                        "valueError" => "The value at the index of 1 is not an int. Only ints are permitted for this parameter. Your value is a float."
                    ],
                    [
                        "value" => "fugue",
                        "valueError" => "The value at the index of 3 is not an int. Only ints are permitted for this parameter. Your value is a string."
                    ],
                ],
            ],    
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => [13, 6],
                "originalIntString" => "13,6.87,6,fugue::IN",
                "comparisonOperatorCoveredTo" => "in",
                "originalComparisonOperator" => "IN",
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => [13, 6],
                "comparisonOperator" => "in",
                "originalComparisonOperator" => "IN",
            ]
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_validate_function_get_all_data()
    {
        $comparisonOperator = 'IN';
        $int = '13,6.87,6,fugue';
        $intString = $int . '::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedRejectedParameters = [
            'team_id' => [
                'intCoveredTo' => [13,6],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'in',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                        "value" => 6.87,
                        "valueError" => "The value at the index of 1 is not an int. Only ints are permitted for this parameter. Your value is a float."
                    ],
                    [
                        "value" => "fugue",
                        "valueError" => "The value at the index of 3 is not an int. Only ints are permitted for this parameter. Your value is a string."
                    ],
                ],
            ],    
        ];

        $expectedAcceptedParameters = [
            "team_id" => [
                "intCoveredTo" => [13, 6],
                "originalIntString" => "13,6.87,6,fugue::IN",
                "comparisonOperatorCoveredTo" => "in",
                "originalComparisonOperator" => "IN",
            ]
        ];

        $expectedQueryArguments = [
            [
                "dataType" => "int",
                "columnName" => "team_id",
                "int" => [13, 6],
                "comparisonOperator" => "in",
                "originalComparisonOperator" => "IN",
            ]
        ];

        $expectedGetAllData = [
            'endpointData' => null,
            'acceptedParameters' => $expectedAcceptedParameters,
            'rejectedParameters' => $expectedRejectedParameters,
            'queryArguments' => $expectedQueryArguments,
        ];
        
        $this->validatorDataCollector = $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData); 

        // dd($this->validatorDataCollector->getAllData());

        $this->assertEquals($expectedGetAllData, $this->validatorDataCollector->getAllData());
    }

    // ! start here ***************************************************88
    // TODO: 
    // test getQueryArguments on this test and others***** Look for expectedReturnData
    // test getAllData for dates
    // look over all my code
        // IntParameterValidatorTest.php Done
}
