<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (sub IntParameterValidator, DateParameterValidator, PostRequestMethodTypeValidator)
// [] read over
// [] add return type : void
// [] add test
// test to do
// [] read over
// [] test groups, rest, context
// [] add return type : void
// [] testing what I need to test

class IntParameterValidator implements ParameterValidator
{
    protected $validatorDataCollector;
    protected $parameterName;
    protected $int;
    protected $originalInt;
    protected $originalComparisonOperator = '';
    protected $intAction;
    protected $processedAsArray = false;
    protected $errors;
    protected $comparisonOperator;

    // TODO: possibly switch to $parameter and $parameterValue from $parameterData???
    public function validate(string $parameterName, string $parameterValue, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->parameterName = $parameterName;
        $this->int = $parameterValue;
        $this->originalInt = $parameterValue;
        
        $this->processIntParameter();
        $this->setComparisonOperator();
        $this->setErrorsIfAny();
        $this->setAcceptedParameterIfAny();
        $this->setQueryArgumentIfAny();
    }

    protected function processIntParameter(): void
    {
        $this->ifParameterHasActionThenSetAction();
        $this->isArrayThenProcessArray();
        $this->isNotArrayThenProcessAsSingleInt();
    }

    // TODO: test details
    protected function ifParameterHasActionThenSetAction(): void
    {
        if (str_contains($this->int, '::')) {
            $intArray = explode('::', $this->int);
    
            $this->originalComparisonOperator = $intArray[1];
            $this->intAction = strtolower($intArray[1]);
            $this->int = $intArray[0];
        }
    }

    // ! Start here ******************************
    // TODO: test or the action/comparison operator was not one fo these "between", "bt", "in", "notin".
    // TODO: test defaults to in
    protected function isArrayThenProcessArray(): void
    {
        if (
            str_contains($this->int, ',') &&
            (
                in_array($this->intAction, ['between', 'bt', 'in', 'notin']) || // TODO: test
                $this->intAction == null
            )
        ) {
            $this->processedAsArray = true;
            $this->intAction = $this->intAction ? $this->intAction : 'in'; // defaults to in // TODO: test
            $ints = explode(',', $this->int);
            $realInts = [];
            foreach ($ints as $index => $value) {
                if ($this->isInt($value)) {
                    $realInts[] = (int) $value;
                } elseif (is_numeric($value)) { // TODO: test
                    $this->errors[] = [
                        'value' => (float) $value,
                        'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a float.",
                    ];
                } else { // TODO: test
                    $this->errors[] = [
                        'value' => $value,
                        'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a string.",
                    ];
                }
            }

            if ($realInts) {
                $this->int = $realInts;
                if (in_array($this->intAction, ['between', 'bt']) && count($this->int) >= 2) {
                    $this->int = [$this->int[0], $this->int[1]]; // TODO: test
                }
            } else {
                $this->errors[] = [
                    'value' => $this->int,
                    'valueError' => 'There are no ints available in this array and/or the action/comparison operator was not one of these "between", "bt", "in", "notin". This parameter was not set.', // TODO: test
                ];
            }
        }
    }

    protected function isNotArrayThenProcessAsSingleInt(): void
    {
        if (!is_array($this->int) && !$this->processedAsArray) {
            if ($this->isInt($this->int)) {
                $this->int = (int) $this->int;
            } elseif (is_numeric($this->int)) {
                $this->errors[] = [
                    'value' => (float) $this->int,
                    'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a float. This parameter was not set.',
                ];
            } else {
                $this->errors[] = [
                    'value' => $this->int,
                    'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string. This parameter was not set.',
                ];
            }
        }
    }

    protected function isInt($value): bool
    {
        return is_numeric($value) && !str_contains($value, '.');
    }

    protected function setComparisonOperator(): void
    {
        if (in_array($this->intAction, ['greaterthan', 'gt', '>'])) {
            $this->comparisonOperator = '>';
        } elseif (in_array($this->intAction, ['greaterthanorequal', 'gte', '>='])) {
            $this->comparisonOperator = '>=';
        } elseif (in_array($this->intAction, ['lessthan', 'lt', '<'])) {
            $this->comparisonOperator = '<';
        } elseif (in_array($this->intAction, ['lessthanorequal', 'lte', '<='])) {
            $this->comparisonOperator = '<=';
        } elseif (in_array($this->intAction, ['between', 'bt'])) {
            $this->comparisonOperator = 'bt';
        } elseif ($this->intAction == 'in') {
            $this->comparisonOperator = 'in';
        } elseif ($this->intAction == 'notin') {
            $this->comparisonOperator = 'notin';
        } else {
            $this->comparisonOperator = '=';
        }

        $this->isBetweenOperatorThenCheckForBetweenErrors();
    }

    protected function isBetweenOperatorThenCheckForBetweenErrors(): void
    {
        if ($this->comparisonOperator == 'bt') {
            $this->isFirstIntGreaterThenLastIntThenThrowError();
            $this->isIntAnArrayAndCountLessThen2ThenThrowError();
        }
    }

    protected function isFirstIntGreaterThenLastIntThenThrowError(): void
    {
        if (
            is_array($this->int) &&
            count($this->int) >= 2 &&
            $this->int[0] >= $this->int[1]
        ) {
            $this->errors[] = [
                'value' => $this->int,
                'valueError' => 'The First int must be smaller then the second int, ex: 10,60::BT. This between action only utilizes the first two array items if more are passed in. This parameter was not set.',
            ];
        }
    }

    protected function isIntAnArrayAndCountLessThen2ThenThrowError(): void
    {
        if (!(is_array($this->int) && count($this->int) >= 2)) {
            $this->errors[] = [
                'value' => $this->int,
                'valueError' => 'The between int action requires two ints, ex: 10,60::BT. This between action only utilizes the first two array items if more are passed in. This parameter was not set.',
            ];;
        }
    }

    protected function setErrorsIfAny(): void
    {
        if ($this->errors) {
            $this->validatorDataCollector->setRejectedParameters([
                "$this->parameterName" => [
                    'intCoveredTo' => $this->int,
                    'originalIntString' => $this->originalInt,
                    'comparisonOperatorCoveredTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                    'parameterError' => $this->errors,
                ]
            ]);
        }
    }

    protected function setAcceptedParameterIfAny(): void
    {
        if (!$this->errors) {
            $this->validatorDataCollector->setAcceptedParameters([
                "$this->parameterName" => [
                    'intCoveredTo' => $this->int,
                    'originalIntString' => $this->originalInt,
                    'comparisonOperatorCoveredTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }

    protected function setQueryArgumentIfAny(): void
    {
        if (!$this->errors) {
            $this->validatorDataCollector->setQueryArgument([
                "$this->parameterName" => [
                    'dataType' => 'int',
                    'columnName' => $this->parameterName,
                    'int' => $this->int,
                    'comparisonOperator' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }
}