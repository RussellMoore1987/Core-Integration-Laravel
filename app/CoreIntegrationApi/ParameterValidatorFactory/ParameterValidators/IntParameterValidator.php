<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (sub DateParameterValidator, PostRequestMethodTypeValidator)
// [x] read over
// [x] add return type : void
// [x] add test
// test to do
// [x] read over
// [x] test groups, rest, context
// [x] add return type : void
// [x] testing what I need to test
// TODO: ask Rami what he thinks about coverage "The between int action requires two ints, ex: 10,60::BT." only covered by LTE

class IntParameterValidator implements ParameterValidator
{
    protected $validatorDataCollector;
    protected $parameterName;
    protected $int;
    protected $originalInt;
    protected $originalComparisonOperator = '';
    protected $intAction;
    protected $processedAsArray = false;
    protected $realInts;
    protected $errors;
    protected $comparisonOperator;

    public function validate(string $parameterName, string $parameterValue, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->parameterName = $parameterName;
        $this->int = $parameterValue;
        $this->originalInt = $parameterValue;
        
        $this->processIntParameter();
        $this->setComparisonOperator();
        $this->isBetweenActionThenValidateAsSuch();
        $this->setErrorsIfAny();
        $this->setAcceptedParameterIfNoErrors();
        $this->setQueryArgumentIfNoErrors();
    }

    protected function processIntParameter(): void
    {
        $this->ifParameterHasActionThenSetAction();
        $this->isArrayThenProcessArray();
        $this->isNotArrayThenProcessAsSingleInt();
    }

    protected function ifParameterHasActionThenSetAction(): void
    {
        if (str_contains($this->int, '::')) {
            $intArray = explode('::', $this->int);

            $errorInt = $this->int;
            $this->originalComparisonOperator = $intArray[1];
            $this->intAction = strtolower($intArray[1]);
            $this->int = $intArray[0];

            if (count($intArray) > 2) {
                $this->errors[] = [
                    'value' => $errorInt,
                    'valueError' => "Only one comparison operator is permitted per parameter, ex: 123::lt.",
                ];
                unset($intArray[0]);
                $this->intAction = 'inconclusive';
                $this->originalComparisonOperator = $intArray;
            }
        }
    }
    
    protected function isArrayThenProcessArray(): void
    {
        if (
            str_contains($this->int, ',') &&
            (
                in_array($this->intAction, ['between', 'bt', 'in', 'notin']) ||
                $this->intAction === null
            )
        ) {
            $this->processedAsArray = true;
            $this->evaluateEachIntInArray();
            $this->isIntArrayValidThenSetElseSetError();
        }
    }

    protected function evaluateEachIntInArray(): void
    {
        $this->intAction = $this->intAction ? $this->intAction : 'in'; // defaults to in
        $ints = explode(',', $this->int);
        $this->realInts = [];
        foreach ($ints as $index => $value) {
            if ($this->isInt($value)) {
                $this->realInts[] = (int) $value;
            } elseif (is_numeric($value)) {
                $this->errors[] = [
                    'value' => (float) $value,
                    'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a float.",
                ];
            } else {
                $this->errors[] = [
                    'value' => $value,
                    'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a string.",
                ];
            }
        }
    }

    protected function isIntArrayValidThenSetElseSetError(): void
    {
        if ($this->realInts) {
            $this->int = $this->realInts;
        } else {
            $this->errors[] = [
                'value' => $this->int,
                'valueError' => 'There are no ints available in this array.',
            ];
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
                    'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a float.',
                ];
            } elseif (str_contains($this->int, ',')) {
                $this->errors[] = [
                    'value' => $this->int,
                    'valueError' => 'Unable to process array of ints. You must use one of the accepted comparison operator such as "between", "bt", "in", or "notin" to process an array.',
                ];
            } else {
                $this->errors[] = [
                    'value' => $this->int,
                    'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string.',
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
        } elseif ($this->intAction === null || in_array($this->intAction, ['equal', 'e', '='])) {
            $this->comparisonOperator = '=';
        } elseif ($this->intAction == 'inconclusive') {
            $this->comparisonOperator = null;
        } else {
            $this->errors[] = [
                'value' => $this->intAction,
                'valueError' => "The comparison operator is invalid. The comparison operator of \"{$this->intAction}\" does not exist for this parameter.",
            ];
        }
    }

    protected function isBetweenActionThenValidateAsSuch(): void
    {
        if (in_array($this->intAction, ['between', 'bt'])) {
            $this->isFirstIntGreaterThanLastIntThenThrowError();
            $this->isIntAnArrayAndCountEqualToTwoThenThrowError();
        }
    }

    protected function isFirstIntGreaterThanLastIntThenThrowError(): void
    {
        if (
            is_array($this->int) &&
            count($this->int) >= 2 &&
            $this->int[0] >= $this->int[1]
        ) {
            $this->errors[] = [
                'value' => $this->int,
                'valueError' => 'The First int must be smaller than the second int, ex: 10,60::BT.',
            ];
        }
    }

    protected function isIntAnArrayAndCountEqualToTwoThenThrowError(): void
    {
        if (!(is_array($this->int) && count($this->int) == 2)) {
            $this->errors[] = [
                'value' => $this->int,
                'valueError' => 'The between int action requires two ints, ex: 10,60::BT.',
            ];
        }
    }

    protected function setErrorsIfAny(): void
    {
        if ($this->errors) {
            $this->validatorDataCollector->setRejectedParameters([
                "$this->parameterName" => [
                    'intConvertedTo' => $this->int,
                    'originalIntString' => $this->originalInt,
                    'comparisonOperatorConvertedTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                    'parameterError' => $this->errors,
                ]
            ]);
        }
    }

    protected function setAcceptedParameterIfNoErrors(): void
    {
        if (!$this->errors) {
            $this->validatorDataCollector->setAcceptedParameters([
                "$this->parameterName" => [
                    'intConvertedTo' => $this->int,
                    'originalIntString' => $this->originalInt,
                    'comparisonOperatorConvertedTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }

    protected function setQueryArgumentIfNoErrors(): void
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
