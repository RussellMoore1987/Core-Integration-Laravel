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
                    [
                        'value' => $this->numString,
                        'valueError' => 'Unable to process array of ints. You must use one of the accepted comparison operator such as "between", "bt", "in", or "notin" to process an array.',
                    ],
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
            'emptyStringError' => ['', '', '=', [$this->stringError('')]],
            'invalidActionError' => [
                'sam', 1, null,
                [
                    [
                        'value' => 'sam',
                        'valueError' => "The comparison operator is invalid. The comparison operator of \"sam\" does not exist for this parameter."
                    ]
                ]
            ],
            'invalidIntArrayAction' => [
                'LTE', '10,56', '<=',
                [
                    [
                        'value' => '10,56',
                        'valueError' => 'Unable to process array of ints. You must use one of the accepted comparison operator such as "between", "bt", "in", or "notin" to process an array.',
                    ]
                ]
            ],
            'onlyOneIntForBetweenOperator' => [
                'bt', 1, 'bt',
                [
                    [
                        'value' => 1,
                        'valueError' => 'The between int action requires two ints, ex: 10,60::BT.'
                    ]
                ]
            ],
            'noIntsForBetweenOperator' => [
                'between', '', 'bt',
                [
                    [
                        'value' => '',
                        'valueError' => $this->valueErrorMassage('string')
                    ],
                    [
                        'value' => '',
                        'valueError' => 'The between int action requires two ints, ex: 10,60::BT.'
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
     * @group get
     */
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
     * @dataProvider nonArrayComparisonOperatorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_sets_correct_non_array_comparison_operators($comparisonOperator, $comparisonOperatorConvertedTo): void
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
            // show that casing does not matter, all comparison operators are converted to string lower, small example below
            'showThatCasingDoesNotMatterUsing_greaterThan' => ['GreAterThaN', '>'],
            'showThatCasingDoesNotMatterUsing_GTE' => ['GtE', '>='],
            'showThatCasingDoesNotMatterUsing_LT' => ['Lt', '<'],
        ];
     }

     /**
     * @dataProvider arrayComparisonOperatorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_sets_correct_array_comparison_operators($comparisonOperator, $comparisonOperatorConvertedTo): void
    {
        $intArray = [1,100];
        $intString = implode(',', $intArray) . '::' . $comparisonOperator;

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => $intArray,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => $comparisonOperatorConvertedTo,
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => $intArray,
                'comparisonOperator' => $comparisonOperatorConvertedTo,
                'originalComparisonOperator' => $comparisonOperator,
            ]
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    public function arrayComparisonOperatorProvider(): array
     {
        return [
            'betweenUsing_between' => ['between', 'bt'],
            'betweenUsing_bt' => ['bt', 'bt'],
            'inUsing_in' => ['in', 'in'],
            'notInUsing_notIn' => ['notIn', 'notin'],
        ];
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
