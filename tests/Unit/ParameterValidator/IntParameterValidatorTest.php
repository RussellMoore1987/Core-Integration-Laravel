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
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_returns_appropriate_error_messages_multi_action_int_array(): void
    {
        $intString = '13,33';
        $fullIntString = $intString . '::bt::lt';

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $intString,
                'originalIntString' => $fullIntString,
                'comparisonOperatorConvertedTo' => null,
                'originalComparisonOperator' => [1 => 'bt', 2 => 'lt'],
                'parameterError' => [
                    [
                        'value' => $fullIntString,
                        'valueError' => 'Only one comparison operator is permitted per parameter, ex: 123::lt.'
                    ],
                    [
                        'value' => $intString,
                        'valueError' => 'Unable to process array of ints. You must use one of the accepted comparison operator such as "between", "bt", "in", or "notin" to process an array.',
                    ]
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $fullIntString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function test_IntParameterValidator_returns_appropriate_error_messages_multi_action_single_int(): void
    {
        $intString = 33;
        $fullIntString = $intString . '::bt::sam\'';

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $intString,
                'originalIntString' => $fullIntString,
                'comparisonOperatorConvertedTo' => null,
                'originalComparisonOperator' => [1 => 'bt', 2 => 'sam\''],
                'parameterError' => [
                    [
                        'value' => $fullIntString,
                        'valueError' => 'Only one comparison operator is permitted per parameter, ex: 123::lt.'
                    ],
                ],
            ],
        ];

        $this->intParameterValidator->validate('team_id', $fullIntString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validate_function_processing_int_array_using_in_by_default(): void
    {
        $intString = '1,100,33,88,99,55';

        $expectedAcceptedParameters = [
            'team_id' => [
                'intConvertedTo' => [1,100,33,88,99,55],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'in',
                'originalComparisonOperator' => '',
            ]
        ];

        $expectedQueryArguments = [
            'team_id' => [
                'dataType' => 'int',
                'columnName' => 'team_id',
                'int' => [1,100,33,88,99,55],
                'comparisonOperator' => 'in',
                'originalComparisonOperator' => '',
            ]
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);
        
        $this->assertEquals($expectedAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals($expectedQueryArguments, $this->validatorDataCollector->getQueryArguments());
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validate_function_where_all_int_array_items_are_bad(): void // TODO: ask Rami more thoro coverage,
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
                    $this->valueErrorIndexMassage('sam', 0, 'string'),
                    $this->valueErrorIndexMassage(6.87, 1, 'float'),
                    $this->valueErrorIndexMassage(0.01, 2, 'float'),
                    $this->valueErrorIndexMassage('fugue', 3, 'string'),
                    [
                        'value' => 'sam,6.87,.01,fugue',
                        'valueError' => 'There are no ints available in this array.'
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
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validate_function_where_we_have_a_mixed_array_of_good_and_bad_items(): void // TODO: ask Rami more thoro coverage,
    {
        $comparisonOperator = 'notIn';
        $int = '13,6.87,6,fugue';
        $intString = $int . '::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => [13,6],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'notin',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    $this->valueErrorIndexMassage(6.87, 1, 'float'),
                    $this->valueErrorIndexMassage('fugue', 3, 'string'),
                ],
            ],
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
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
            'singleIntStringError' => ['', 'I am not a int', '=', [$this->valueErrorMassage('I am not a int')]],
            'singleIntFloatError' => ['gt', 3.9, '>', [$this->valueErrorMassage(3.9, 'float')]],
            'emptyStringError' => ['', '', '=', [$this->valueErrorMassage('')]],
            'invalidActionError' => [
                'sam', 1, null,
                [
                    [
                        'value' => 'sam',
                        'valueError' => "The comparison operator is invalid. The comparison operator of \"sam\" does not exist for this parameter."
                    ]
                ]
            ],
            'invalidIntArrayAction' => [ // TODO: ask Rami more thoro coverage, gt, gte, lt, >, >=, ect...
                'LTE', '10,56', '<=',
                [
                    [
                        'value' => '10,56',
                        'valueError' => 'Unable to process array of ints. You must use one of the accepted comparison operator such as "between", "bt", "in", or "notin" to process an array.',
                    ]
                ]
            ],
            'onlyOneIntForBetweenOperator' => ['bt', 1, 'bt', [$this->betweenIntActionRequiresTwoIntsErrorMassage(1)]],// TODO: ask Rami more thoro coverage, between
            'noIntsForBetweenOperator' => [ 'between', '', 'bt', [ // TODO: ask Rami more thoro coverage, bt
                $this->valueErrorMassage(''),
                $this->betweenIntActionRequiresTwoIntsErrorMassage('')
            ]],
        ];
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

     /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_between_more_Than_two_ints(): void // TODO: ask Rami more thoro coverage, between
    {
        $comparisonOperator = 'BT';
        $intString = '1,100,33::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => [1,100,33],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [$this->betweenIntActionRequiresTwoIntsErrorMassage([1,100,33])],
            ],
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

     /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_with_between_first_int_greater_Than_second_int_error(): void // TODO: ask Rami more thoro coverage, between
    {
        $comparisonOperator = 'BT';
        $intString = '100,33::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => [100,33],
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => 'bt',
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => [
                    [
                        'value' => [100,33],
                        'valueError' => 'The First int must be smaller than the second int, ex: 10,60::BT.',
                    ]
                ],
            ],
        ];
        
        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

     protected function valueErrorMassage(string $value = '', string $dataType = 'string'): array
     {
        return [
            'value' => $value,
            'valueError' => "The value passed in is not an int. Only ints are permitted for this parameter. Your value is a {$dataType}."
        ];
     }

    protected function valueErrorIndexMassage($value, int $index, string $dataType): array
    {
        return [
            'value' => $value,
            'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a {$dataType}."
        ];
    }

    protected function betweenIntActionRequiresTwoIntsErrorMassage($value): array
    {
        return [
            'value' => $value,
            'valueError' => 'The between int action requires two ints, ex: 10,60::BT.'
        ];
    }
}
