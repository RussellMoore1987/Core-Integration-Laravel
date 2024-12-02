<?php

namespace Tests\Unit\ParameterValidator;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ComparisonOperatorProvider;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ActionFinder;
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
        $this->intParameterValidator = new IntParameterValidator(new ComparisonOperatorProvider(), new ErrorCollector(), new ActionFinder());
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
                    $this->unableToProcessArrayOfIntsErrorMassage($intString)
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
     * @dataProvider inNotInErrorIntArrayProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validate_function_array_items_errors_in_notin($int, $intConvertedTo, $comparisonOperator, $parameterError): void
    {
        $intString = $int . '::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $intConvertedTo,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => $comparisonOperator,
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => $parameterError,
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function inNotInErrorIntArrayProvider(): array
    {
        $int1 = 'sam,6.87,.01,fugue';
        $int2 = '13,6.87,6,fugue';

        return [
            'inPathAllIntItemsAreBad' => [$int1, $int1, 'in', [
                $this->valueErrorIndexMassage('sam', 0, 'string'),
                $this->valueErrorIndexMassage(6.87, 1, 'float'),
                $this->valueErrorIndexMassage(0.01, 2, 'float'),
                $this->valueErrorIndexMassage('fugue', 3, 'string'),
                $this->noIntsAvailableInArrayErrorMassage($int1),
            ]],
            'notInPathAllIntItemsAreBad' => [$int1, $int1, 'notin', [
                $this->valueErrorIndexMassage('sam', 0, 'string'),
                $this->valueErrorIndexMassage(6.87, 1, 'float'),
                $this->valueErrorIndexMassage(0.01, 2, 'float'),
                $this->valueErrorIndexMassage('fugue', 3, 'string'),
                $this->noIntsAvailableInArrayErrorMassage($int1),
            ]],
            'inPathMixedArrayGoodAndBadItems' => [$int2, [13,6], 'in', [
                $this->valueErrorIndexMassage(6.87, 1, 'float'),
                $this->valueErrorIndexMassage('fugue', 3, 'string'),
            ]],
            'notInPathMixedArrayGoodAndBadItems' => [$int2, [13,6], 'notin', [
                $this->valueErrorIndexMassage(6.87, 1, 'float'),
                $this->valueErrorIndexMassage('fugue', 3, 'string'),
            ]],
        ];
    }

    /**
     * @dataProvider btBetweenErrorIntArrayProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validate_function_array_items_errors_bt_between($options, $parameterError): void
    {

        $int = $options[0];
        $intConvertedTo = $options[1];
        $comparisonOperator = $options[2];
        $comparisonOperatorConvertedTo = $options[3];
        $intString = $int . '::' . $comparisonOperator;

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => $intConvertedTo,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => $comparisonOperatorConvertedTo,
                'originalComparisonOperator' => $comparisonOperator,
                'parameterError' => $parameterError,
            ],
        ];

        $this->intParameterValidator->validate('team_id', $intString, $this->validatorDataCollector);

        $this->assertEquals($expectedRejectedParameters, $this->validatorDataCollector->getRejectedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
        $this->assertEquals([], $this->validatorDataCollector->getQueryArguments());
    }

    public function btBetweenErrorIntArrayProvider(): array
    {
        $int1 = 'Sammy,7.85';
        $int2 = 'Sam,7';
        $int2ConvertedTo = [7];

        return [
            'btPathAllIntItemsAreBad' => [[$int1, $int1, 'bt', 'bt'], [
                $this->valueErrorIndexMassage('Sammy', 0, 'string'),
                $this->valueErrorIndexMassage(7.85, 1, 'float'),
                $this->noIntsAvailableInArrayErrorMassage($int1),
                $this->betweenIntActionRequiresTwoIntsErrorMassage($int1),
            ]],
            'betweenPathAllIntItemsAreBad' => [[$int1, $int1, 'between', 'bt'], [
                $this->valueErrorIndexMassage('Sammy', 0, 'string'),
                $this->valueErrorIndexMassage(7.85, 1, 'float'),
                $this->noIntsAvailableInArrayErrorMassage($int1),
                $this->betweenIntActionRequiresTwoIntsErrorMassage($int1),
            ]],
            'btPathMixedArrayGoodAndBadItems' => [[$int2, $int2ConvertedTo, 'bt', 'bt'], [
                $this->valueErrorIndexMassage('Sam', 0, 'string'),
                $this->betweenIntActionRequiresTwoIntsErrorMassage($int2ConvertedTo),
            ]],
            'betweenPathMixedArrayGoodAndBadItems' => [[$int2, $int2ConvertedTo, 'between', 'bt'], [
                $this->valueErrorIndexMassage('Sam', 0, 'string'),
                $this->betweenIntActionRequiresTwoIntsErrorMassage($int2ConvertedTo),
            ]],
        ];
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validate_function_where_we_have_no_comparison_operator_int_value(): void
    {
        $int = '13';
        $intString = $int . '::';

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => 13,
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => null,
                'originalComparisonOperator' => '',
                'parameterError' => [
                    $this->invalidComparisonOperatorErrorMassage(),
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
    public function test_IntParameterValidator_validate_function_where_we_have_no_comparison_operator_array_of_ints_value(): void
    {
        $int = '13,22,55';
        $intString = $int . '::';

        $expectedRejectedParameters = [
            'team_id' => [
                'intConvertedTo' => '13,22,55',
                'originalIntString' => $intString,
                'comparisonOperatorConvertedTo' => null,
                'originalComparisonOperator' => '',
                'parameterError' => [
                    $this->unableToProcessArrayOfIntsErrorMassage($int),
                    $this->invalidComparisonOperatorErrorMassage(),
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
            'invalidActionError' => ['sam', 1, null,[$this->invalidComparisonOperatorErrorMassage('sam')]],
            'invalidIntArrayActionWith_equal' => $this->invalidIntArrayActionErrorProvider('equal', '='),
            'invalidIntArrayActionWith_e' => $this->invalidIntArrayActionErrorProvider('e', '='),
            'invalidIntArrayActionWith_=' => $this->invalidIntArrayActionErrorProvider('=', '='),
            'invalidIntArrayActionWith_greaterThan' => $this->invalidIntArrayActionErrorProvider('greaterThan', '>'),
            'invalidIntArrayActionWith_gt' => $this->invalidIntArrayActionErrorProvider('gt', '>'),
            'invalidIntArrayActionWith_>' => $this->invalidIntArrayActionErrorProvider('>', '>'),
            'invalidIntArrayActionWith_greaterThanOrEqual' => $this->invalidIntArrayActionErrorProvider('greaterThanOrEqual', '>='),
            'invalidIntArrayActionWith_gte' => $this->invalidIntArrayActionErrorProvider('gte', '>='),
            'invalidIntArrayActionWith_>=' => $this->invalidIntArrayActionErrorProvider('>=', '>='),
            'invalidIntArrayActionWith_lessThan' => $this->invalidIntArrayActionErrorProvider('lessThan', '<'),
            'invalidIntArrayActionWith_lt' => $this->invalidIntArrayActionErrorProvider('lt', '<'),
            'invalidIntArrayActionWith_<' => $this->invalidIntArrayActionErrorProvider('<', '<'),
            'invalidIntArrayActionWith_lessThanOrEqual' => $this->invalidIntArrayActionErrorProvider('lessThanOrEqual', '<='),
            'invalidIntArrayActionWith_lte' => $this->invalidIntArrayActionErrorProvider('lte', '<='),
            'invalidIntArrayActionWith_<=' => $this->invalidIntArrayActionErrorProvider('<=', '<='),
            'onlyOneIntForBetweenOperator_bt' => ['bt', 1, 'bt', [$this->betweenIntActionRequiresTwoIntsErrorMassage(1)]],
            'onlyOneIntForBetweenOperator_between' => ['between', 1, 'bt', [$this->betweenIntActionRequiresTwoIntsErrorMassage(1)]],
            'noIntsForBetweenOperator_bt' => [ 'bt', '', 'bt', [
                $this->valueErrorMassage(''),
                $this->betweenIntActionRequiresTwoIntsErrorMassage('')
            ]],
            'noIntsForBetweenOperator_between' => [ 'between', '', 'bt', [
                $this->valueErrorMassage(''),
                $this->betweenIntActionRequiresTwoIntsErrorMassage('')
            ]],
        ];
     }

     protected function invalidIntArrayActionErrorProvider($comparisonOperator, $comparisonOperatorConvertedTo): array
     {
        return [ $comparisonOperator, '10,56', $comparisonOperatorConvertedTo,[$this->unableToProcessArrayOfIntsErrorMassage('10,56')]];
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
     * @dataProvider betweenComparisonOperatorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_validate_function_with_between_more_than_two_ints($comparisonOperator): void
    {
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
     * @dataProvider betweenComparisonOperatorProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IntParameterValidator_with_between_first_int_greater_than_second_int_error($comparisonOperator): void
    {
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

    public function betweenComparisonOperatorProvider(): array
    {
        return [
            'betweenUsing_between' => ['between'],
            'betweenUsing_bt' => ['bt'],
        ];
    }

    protected function unableToProcessArrayOfIntsErrorMassage($intString): array
    {
        return [
            'value' => $intString,
            'valueError' => 'Unable to process array of ints. You must use one of the accepted comparison operator such as "between", "bt", "in", or "notin" to process an array.',
        ];
    }

    protected function valueErrorIndexMassage($value, int $index, string $dataType): array
    {
        return [
            'value' => $value,
            'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a {$dataType}."
        ];
    }

    protected function noIntsAvailableInArrayErrorMassage($value): array
    {
        return [
            'value' => $value,
            'valueError' => 'There are no ints available in this array.'
        ];
    }

    protected function betweenIntActionRequiresTwoIntsErrorMassage($value): array
    {
        return [
            'value' => $value,
            'valueError' => 'The between int action requires two ints, ex: 10,60::BT.'
        ];
    }

    protected function invalidComparisonOperatorErrorMassage($value = ''): array
    {
        return [
            'value' => $value,
            'valueError' => "The comparison operator is invalid. The comparison operator of \"{$value}\" does not exist for this parameter."
        ];
    }

    protected function valueErrorMassage(string $value = '', string $dataType = 'string'): array
    {
        return [
            'value' => $value,
            'valueError' => "The value passed in is not an int. Only ints are permitted for this parameter. Your value is a {$dataType}."
        ];
    }
}
