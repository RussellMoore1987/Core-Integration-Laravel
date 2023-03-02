<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Tests\TestCase;

class IntParameterValidatorTest extends TestCase
{
    protected $validatorDataCollector;
    protected $intParameterValidator;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = new ValidatorDataCollector();
        $this->intParameterValidator = new IntParameterValidator();
    }

    















    /**
     * @group get
     */
    // TODO: combine into one sets errors???
    public function test_IntParameterValidator_validate_function_with_random_string(): void
    {
        $comparisonOperator = '';
        $intString = 'I am not a int' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $intString,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => 'I am not a int',
                      'valueError' => $this->valueErrorMassage('string')
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets errors???
    public function test_IntParameterValidator_validate_function_with_extra_action(): void
    {
        $intString = "13,33::bt::lt";
        // $intString = "33::bt::sam'"; // TODO: test this
        // $intString = "33::bt::lt"; // TODO: test this

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => '13,33',
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => null,
                'originalComparisonOperator' => [
                    1 => 'bt',
                    2 => 'lt'
                ],
                'parameterError' => [
                    [
                        'value' => $intString,
                        'valueError' => 'Only one comparison operator is permitted per parameter, ex: 123::lt.'
                    ],
                    [
                        'value' => '13,33',
                        'valueError' => $this->valueErrorMassage('string')
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        // dd($this->validatorDataCollector->getRejectedParameters());

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets errors???
    public function test_IntParameterValidator_validate_function_with_no_int_blank_string(): void
    {
        $comparisonOperator = '';
        $intString = '' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $intString,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '=',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => '',
                      'valueError' => $this->valueErrorMassage('string')
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_equal_to_with_out_action_operator(): void
    {
        $comparisonOperator = '';
        $intString = '1' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 1,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '=',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator *****error???
    public function test_IntParameterValidator_validate_function_with_equal_to_by_default(): void
    {
        $comparisonOperator = 'sam';
        $intString = '1::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => 1,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => null,
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                        'value' => $comparisonOperator,
                        'valueError' => "The comparison operator is invalid. The comparison operator of \"{$comparisonOperator}\" does not exist for this parameter."
                    ]
                ],
            ],
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_greater_Than_using_gt(): void
    {
        $comparisonOperator = 'GT';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '>',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_greater_Than_using_greater_than(): void
    {
        $comparisonOperator = 'greaterThan';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '>',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_greater_Than_using_greater_than_symbol(): void
    {
        $comparisonOperator = '>';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '>',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_greater_Than_or_equal_to_using_greater_than_or_equal(): void
    {
        $comparisonOperator = 'greaterThanOrEqual';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '>=',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_greater_Than_or_equal_to_using_gte(): void
    {
        $comparisonOperator = 'GTE';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '>=',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_greater_Than_or_equal_to_using_greater_than_or_equal_symbol(): void
    {
        $comparisonOperator = '>=';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '>=',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_less_Than_using_less_than(): void
    {
        $comparisonOperator = 'lessThan';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '<',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_less_Than_using_less_than_symbol(): void
    {
        $comparisonOperator = '<';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '<',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_less_Than_using_lt(): void
    {
        $comparisonOperator = 'lt';
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '<',
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

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_less_than_or_equal(): void
    {
        $comparisonOperator = 'lessThanOrEqual';
        $intString = '10::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 10,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '<=',
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

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_less_than_or_equal_symbol(): void
    {
        $comparisonOperator = '<=';
        $intString = '10::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 10,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '<=',
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

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_less_than_or_equal_to_using_lte(): void
    {
        $comparisonOperator = 'LTE';
        $intString = '10::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 10,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '<=',
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

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets errors???
    // TODO: test error of this 4,3:: or sam:: -> takes care of this
    public function test_IntParameterValidator_validate_function_with_action_that_is_not_in_notin_or_bt(): void
    {
        // arrays or int strings that have a comma are only used in these actions IN, NotIn, BT, or Between
        $comparisonOperator = 'LTE';
        $int = '10,56';
        $intString = $int . '::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $int,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => '<=',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => $int,
                      'valueError' => $this->valueErrorMassage('string')
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_between(): void
    {
        $comparisonOperator = 'bt';
        $intString = '1,100::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => [1,100],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'bt',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets comparisonOperator
    public function test_IntParameterValidator_validate_function_with_between_error_first_int_grater_than_last_int(): void
    {
        $comparisonOperator = 'between';
        $intString = '100,1::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => [100,1],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => [100,1],
                      'valueError' => 'The First int must be smaller than the second int, ex: 10,60::BT.'
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets errors???
    public function test_IntParameterValidator_validate_function_with_between_error_no_second_int(): void
    {
        $comparisonOperator = 'BETWEEN';
        $intString = '1::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => 1,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => 1,
                      'valueError' => 'The between int action requires two ints, ex: 10,60::BT.'
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets errors???
    public function test_IntParameterValidator_validate_function_with_between_error_no_ints(): void
    {
        $comparisonOperator = 'BT';
        $intString = '::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => '',
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => '',
                      'valueError' => $this->valueErrorMassage('string')
                    ],
                    [
                      'value' => '',
                      'valueError' => 'The between int action requires two ints, ex: 10,60::BT.'
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    // TODO: combine into one sets errors???
    public function test_IntParameterValidator_validate_function_with_between_more_Than_two_ints(): void
    {
        $comparisonOperator = 'BT';
        $intString = '1,100,33::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => [1,100,33],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                      'value' => [1,100,33],
                      'valueError' => 'The between int action requires two ints, ex: 10,60::BT.'
                    ],
                ],
            ],
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_in(): void
    {
        $comparisonOperator = 'in';
        $intString = '1,100,33,88,99,55::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => [1,100,33,88,99,55],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'in',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_in_by_default(): void
    {
        $comparisonOperator = '';
        $intString = '1,100,33,88,99,55' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => [1,100,33,88,99,55],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'in',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_not_in(): void
    {
        $comparisonOperator = 'notIn';
        $intString = '1,100,33,88,99,55::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => [1,100,33,88,99,55],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'notin',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_where_all_array_items_are_bad(): void
    {
        $comparisonOperator = 'IN';
        $int = 'sam,6.87,.01,fugue';
        $intString = $int . '::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $int,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'in',
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
                        'valueError' => 'There are no ints available in this array and/or the action/comparison operator was not one of these "between", "bt", "in", "notin".'
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_where_we_have_a_mixed_array_of_good_and_bad_items(): void
    {
        $comparisonOperator = 'IN';
        $int = '13,6.87,6,fugue';
        $intString = $int . '::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => [13,6],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'in',
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
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group get
     */
    public function test_IntParameterValidator_validate_function_get_all_data(): void
    {
        $comparisonOperator = 'IN';
        $int = '13,7,6,33';
        $intString = $int . '::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => [13,7,6,33],
                'originalIntString' => '13,7,6,33::IN',
                'comparisonOperatorConvertedTo' => 'in',
                'originalComparisonOperator' => 'IN',
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [13,7,6,33],
                'comparisonOperator' => 'in',
                'originalComparisonOperator' => 'IN',
            ]
        ];

        $expectedGetValidatedMetaData = [
            'endpointData' => [],
            'resourceInfo' => [],
            'acceptedParameters' => $expectedAcceptedParameters,
            'rejectedParameters' => [],
            'queryArguments' => $expectedQueryArguments,
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedGetValidatedMetaData, $this->validatorDataCollector->getValidatedMetaData());
    }

    protected function valueErrorIndexMassage(int $index, string $dataType): string
    {
        return "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a {$dataType}.";
    }
    protected function valueErrorMassage(string $dataType): string
    {
        return "The value passed in is not an int. Only ints are permitted for this parameter. Your value is a {$dataType}.";
        
    }
}
