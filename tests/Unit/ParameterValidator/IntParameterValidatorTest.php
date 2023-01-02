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

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_random_string() : void
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
                      'valueError' => $this->valueErrorMassage('string')
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_extra_action() : void
    {
        $intString = "13,33::bt::something'";
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => [13,33],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => 'bt',
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [13,33],
                'comparisonOperator' => 'bt',
                'originalComparisonOperator' => 'bt',
            ]
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_no_int_blank_string() : void
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
                      'valueError' => $this->valueErrorMassage('string')
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_equal_to_with_out_action_operator() : void
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
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 1,
                'comparisonOperator' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_equal_to_by_default() : void
    {
        $comparisonOperator = 'sam';
        $intString = '1::' . $comparisonOperator;
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
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 1,
                'comparisonOperator' => '=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_greater_then_using_gt() : void
    {
        $comparisonOperator = 'GT';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_greater_then_using_greater_than() : void
    {
        $comparisonOperator = 'greaterThan';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_greater_then_using_greater_than_symbol() : void
    {
        $comparisonOperator = '>';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '>',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_greater_then_or_equal_to_using_greater_than_or_equal() : void
    {
        $comparisonOperator = 'greaterThanOrEqual';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_greater_then_or_equal_to_using_gte() : void
    {
        $comparisonOperator = 'GTE';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_greater_then_or_equal_to_using_greater_than_or_equal_symbol() : void
    {
        $comparisonOperator = '>=';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '>=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_less_then_using_less_than() : void
    {
        $comparisonOperator = 'lessThan';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_less_then_using_less_than_symbol() : void
    {
        $comparisonOperator = '<';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_less_then_using_lt() : void
    {
        $comparisonOperator = 'lt';
        $intString = '4::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => '<',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_less_than_or_equal() : void
    {
        $comparisonOperator = 'lessThanOrEqual';
        $intString = '10::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 10,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 10,
                'comparisonOperator' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_less_than_or_equal_symbol() : void
    {
        $comparisonOperator = '<=';
        $intString = '10::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 10,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 10,
                'comparisonOperator' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_lte() : void
    {
        $comparisonOperator = 'LTE';
        $intString = '10::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => 10,
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 10,
                'comparisonOperator' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_action_that_is_not_in_notin_or_bt() : void
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
                      'valueError' => $this->valueErrorMassage('string')
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_between() : void
    {
        $comparisonOperator = 'bt';
        $intString = '1,100::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => [1,100],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [1,100],
                'comparisonOperator' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_between_error_first_int_grater_than_last_int() : void
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

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_between_error_no_second_int() : void
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

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_between_error_no_ints() : void
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
                      'valueError' => $this->valueErrorMassage('string')
                    ],
                    [
                      'value' => '',
                      'valueError' => 'The between int action requires two ints, ex: 10,60::BT. This between action only utilizes the first two array items if more are passed in. This parameter was not set.'
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_between_more_then_two_ints() : void
    {
        $comparisonOperator = 'BT';
        $intString = '1,100,33::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => [1,100],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [1,100],
                'comparisonOperator' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_in() : void
    {
        $comparisonOperator = 'in';
        $intString = '1,100,33,88,99,55::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => [1,100,33,88,99,55],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'in',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [1,100,33,88,99,55],
                'comparisonOperator' => 'in',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_in_by_default() : void
    {
        $comparisonOperator = '';
        $intString = '1,100,33,88,99,55' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => [1,100,33,88,99,55],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'in',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [1,100,33,88,99,55],
                'comparisonOperator' => 'in',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_not_in() : void
    {
        $comparisonOperator = 'notIn';
        $intString = '1,100,33,88,99,55::' . $comparisonOperator;
        $parameterData = [
            'team_id' => $intString
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => [1,100,33,88,99,55],
                'originalIntString' => $intString,
                'comparisonOperatorCoveredTo' => 'notin',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [1,100,33,88,99,55],
                'comparisonOperator' => 'notin',
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_where_all_array_items_are_bad() : void
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
                        'value' => 'sam',
                        'valueError' => $this->valueErrorIndexMassage(0, 'string')
                    ],
                    [
                        'value' => 6.87,
                        'valueError' => $this->valueErrorIndexMassage(1, 'float')
                    ],
                    [
                        'value' => 0.01,
                        'valueError' => $this->valueErrorIndexMassage(2, 'float')
                    ],
                    [
                        'value' => 'fugue',
                        'valueError' => $this->valueErrorIndexMassage(3, 'string')
                    ],
                    [
                        'value' => 'sam,6.87,.01,fugue',
                        'valueError' => 'There are no ints available in this array. This parameter was not set.'
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_where_we_have_a_mixed_array_of_good_and_bad_items() : void
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
                        'value' => 6.87,
                        'valueError' => $this->valueErrorIndexMassage(1, 'float')
                    ],
                    [
                        'value' => 'fugue',
                        'valueError' => $this->valueErrorIndexMassage(3, 'string')
                    ],
                ],
            ],
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => [13, 6],
                'originalIntString' => '13,6.87,6,fugue::IN',
                'comparisonOperatorCoveredTo' => 'in',
                'originalComparisonOperator' => 'IN',
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [13, 6],
                'comparisonOperator' => 'in',
                'originalComparisonOperator' => 'IN',
            ]
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_get_all_data() : void
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
                        'value' => 6.87,
                        'valueError' => $this->valueErrorIndexMassage(1, 'float')
                    ],
                    [
                        'value' => 'fugue',
                        'valueError' => $this->valueErrorIndexMassage(3, 'string')
                    ],
                ],
            ],
        ];

        $expectedAcceptedParameters = [
            'team_id' => [
                'intCoveredTo' => [13, 6],
                'originalIntString' => '13,6.87,6,fugue::IN',
                'comparisonOperatorCoveredTo' => 'in',
                'originalComparisonOperator' => 'IN',
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [13, 6],
                'comparisonOperator' => 'in',
                'originalComparisonOperator' => 'IN',
            ]
        ];

        $expectedGetValidatedMetaData = [
            'endpointData' => [],
            'resourceInfo' => [],
            'acceptedParameters' => $expectedAcceptedParameters,
            'rejectedParameters' => $expectedRejectedParameters,
            'queryArguments' => $expectedQueryArguments,
        ];
        
        $this->intParameterValidator->validate($this->validatorDataCollector, $parameterData);

        $this->assertEquals($expectedGetValidatedMetaData, $this->validatorDataCollector->getValidatedMetaData());
    }

    protected function valueErrorIndexMassage(int $index, string $dataType) : string
    {
        return "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a {$dataType}.";
    }
    protected function valueErrorMassage(string $dataType) : string
    {
        return "The value passed in is not an int. Only ints are permitted for this parameter. Your value is a {$dataType}. This parameter was not set.";
        
    }
}
