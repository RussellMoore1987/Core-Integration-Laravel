<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Tests\TestCase;

class IntParameterValidatorTest extends TestCase
{
    protected $validatorDataCollector;
    protected $intParameterValidator;
    protected $numString = '13,33';
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = new ValidatorDataCollector();
        $this->intParameterValidator = new IntParameterValidator();
    }

    /**
     * @dataProvider multiActionErrorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validation_returns_appropriate_error_messages_multi_action($comparisonOperator, $intString, $errors): void
    {
        $comparisonOperatorString = $comparisonOperator ? '::' . implode('::', $comparisonOperator) : '';
        $fullIntString = $intString . $comparisonOperatorString;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $intString,
                'originalIntString' => $fullIntString,
                'comparisonOperatorConvertedTo' => null,
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => $errors,
            ],
        ];

        $this->intParameterValidator->validate('team_id', $fullIntString, $this->validatorDataCollector);

        // dd($this->validatorDataCollector->getRejectedParameters());

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function multiActionErrorProvider(): array
     {
        return [
            'intArrayAndTooManyActionsSent' => [
                [1 => 'bt', 2 => 'lt'],
                $this->numString,
                [
                    [
                        'value' => $this->numString . '::bt::lt',
                        'valueError' => 'Only one comparison operator is permitted per parameter, ex: 123::lt.'
                    ],
                    $this->stringError($this->numString),
                ]
            ],
            'intAndTooManyActionsSent' => [
                [1 => 'bt', 2 => 'sam\''],
                33,
                [
                    [
                        'value' => 33 . '::bt::sam\'',
                        'valueError' => 'Only one comparison operator is permitted per parameter, ex: 123::lt.'
                    ],
                ]
            ],
        ];
     }


    /**
     * @dataProvider intParameterValidatorErrorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validation_returns_appropriate_error_messages($comparisonOperator, $intString, $comparisonOperatorConvertedTo, $errors): void
    {
        $comparisonOperatorString = $comparisonOperator ? '::'.$comparisonOperator : '';
        $fullIntString = $intString . $comparisonOperatorString;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $intString,
                'originalIntString' => $fullIntString,
                'comparisonOperatorConvertedTo' => $comparisonOperatorConvertedTo,
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => $errors,
            ],
        ];

        $this->intParameterValidator->validate('team_id', $fullIntString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function intParameterValidatorErrorProvider(): array
     {
        return [
            'stringError' => ['', 'I am not a int', '=', [$this->stringError('I am not a int')]],
            'emptyString' => ['', '', '=', [$this->stringError('')]],
            'invalidAction' => [
                'sam', 1, null,
                [
                    [
                        'value' => 'sam',
                        'valueError' => "The comparison operator is invalid. The comparison operator of \"sam\" does not exist for this parameter."
                    ]
                ]
            ],
        ];
     }

     protected function stringError(string $string = ''): array
     {
        return [
            'value' => $string,
            'valueError' => $this->valueErrorMassage('string')
        ];
     }

    /**
     * @dataProvider nonArrayComparisonOperatorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_sets_correct_comparison_operator($comparisonOperator, $comparisonOperatorConvertedTo): void
    {
        $intString = '4::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => 4,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => $comparisonOperatorConvertedTo,
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => 4,
                'comparisonOperator' => $comparisonOperatorConvertedTo,
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function nonArrayComparisonOperatorProvider(): array
     {
        return [
            'equalsUsing_equal' => ['equal', '='],
            'equalsUsing__e' => ['e', '='],
            'equalsUsing_=' => ['=', '='],
            'greaterThanUsing_greaterThan' => ['greaterThan', '>'],
            'greaterThanUsing_gt' => ['gt', '>'],
            'greaterThanUsing_>' => ['>', '>'],
            'greaterThanOrEqualUsing_greaterThanOrEqual' => ['greaterThanOrEqual', '>='],
            'greaterThanOrEqualUsing_gt' => ['gte', '>='],
            'greaterThanOrEqualUsing_>=' => ['>=', '>='],
            'lessThanUsing_lessThan' => ['lessThan', '<'],
            'lessThanUsing_lt' => ['lt', '<'],
            'lessThanUsing_<' => ['<', '<'],
            'lessThanOrEqualUsing_lessThanOrEqual' => ['lessThanOrEqual', '<='],
            'lessThanOrEqualUsing_lte' => ['lte', '<='],
            'lessThanOrEqualUsing_<=' => ['<=', '<='],
            // show that casing does not matter, all comparison operators are converted to string lower small example below
            'showThatCasingDoesNotMatterUsing_greaterThan' => ['GreAterThaN', '>'],
            'showThatCasingDoesNotMatterUsing_GTE' => ['GTE', '>='],
            'showThatCasingDoesNotMatterUsing_LT' => ['LT', '<'],
        ];
     }

    /**
     * @group get
     */
    // TODO: combine into one sets errors???
    // TODO: test error of this 4,3::LTE or sam:: -> takes care of this, array::notArrayAction
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
