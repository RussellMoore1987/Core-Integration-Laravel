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
    private $validatorDataCollector;
    private $columnName;
    private $int;
    private $originalInt;
    private $originalComparisonOperator = '';
    private $intAction;
    private $comparisonOperator;
    private $errors;
    private $processedAsArray = false;
    private $requestError = false;

    // TODO: possibly switch to $parameter and $parameterValue from $parameterData???
    public function validate(ValidatorDataCollector &$validatorDataCollector, $parameterData): void
    {
        $this->setMainVariables($validatorDataCollector, $parameterData);
        $this->processData();
        $this->checkForOtherErrors();
        $this->setErrorsIfAny();
        $this->setAcceptedParameterIfAny();
        $this->setDataQueryArgumentIfAny();
    }

    private function setMainVariables($validatorDataCollector, $parameterData): void
    {
        $this->validatorDataCollector = $validatorDataCollector;

        foreach ($parameterData as $columnName => $int) { // we should only have one array item
            $this->columnName = $columnName;
            $this->int = $int;
            $this->originalInt = $int;
        }
    }

    private function processData(): void
    {
        $this->processIntString();
        $this->setComparisonOperator();
    }

    private function processIntString(): void
    {
        $this->seeIfParameterHasAction();
        $this->seeIfParameterHasArrayProcessAccordingly();
    }

    private function seeIfParameterHasAction(): void
    {
        if (str_contains($this->int, '::')) {
            $intArray = explode('::', $this->int);
    
            $this->originalComparisonOperator = $intArray[1];
            $this->intAction = strtolower($intArray[1]);
            $this->int = $intArray[0];
        }
    }

    private function seeIfParameterHasArrayProcessAccordingly(): void
    {
        $this->ifArray();
        $this->ifNotArray();
    }

    private function ifArray(): void
    {
        if (
            str_contains($this->int, ',') &&
            (
                in_array($this->intAction, ['between', 'bt', 'in', 'notin']) ||
                $this->intAction == null
            )
        ) {
            $this->intAction = $this->intAction ? $this->intAction : 'in';
            $ints = explode(',', $this->int);
            $realInts = [];
            foreach ($ints as $index => $value) {
                if ($this->isInt($value)) {
                    $realInts[] = (int) $value;
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

            if ($realInts) {
                $this->int = $realInts;
                if ($this->intAction == 'bt' && count($this->int) >= 2) {
                    $this->int = [$this->int[0], $this->int[1]];
                }
            } else {
                $this->requestError = true;
                $this->processedAsArray = true;
                $this->errors[] = [
                    'value' => $this->int,
                    'valueError' => 'There are no ints available in this array. This parameter was not set.',
                ];
            }
        }
    }

    private function isInt($value): bool
    {
        return is_numeric($value) && !str_contains($value, '.');
    }

    private function ifNotArray(): void
    {
        if (!is_array($this->int) && !$this->processedAsArray) {
            if ($this->isInt($this->int)) {
                $this->int = (int) $this->int;
            } elseif (is_numeric($this->int)) {
                $this->requestError = true;
                $this->errors[] = [
                    'value' => (float) $this->int,
                    'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a float. This parameter was not set.',
                ];
            } else {
                $this->requestError = true;
                $this->errors[] = [
                    'value' => $this->int,
                    'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string. This parameter was not set.',
                ];
            }
        }
    }

    private function setComparisonOperator(): void
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
    }

    private function checkForOtherErrors(): void
    {
        $this->validateBetweenIntsAreCorrect();
    }

    private function validateBetweenIntsAreCorrect(): void
    {
        $this->checkToSeeIfFirstIntIsGreaterThenLastInt();
        $this->checkToSeeIfWeHaveTwoInts();
    }

    private function checkToSeeIfFirstIntIsGreaterThenLastInt(): void
    {
        if (
            $this->comparisonOperator == 'bt' &&
            is_array($this->int) &&
            count($this->int) >= 2 &&
            $this->int[0] >= $this->int[1]
        ) {
            $this->requestError = true;
            $this->errors[] = [
                'value' => [$this->int[0], $this->int[1]],
                'valueError' => 'The First int must be smaller then the second int, ex: 10,60::BT. This between action only utilizes the first two array items if more are passed in. This parameter was not set.',
            ];
        }
    }

    private function checkToSeeIfWeHaveTwoInts(): void
    {
        if (
            $this->comparisonOperator == 'bt' &&
            !(
                is_array($this->int) &&
                count($this->int) >= 2
            )
        ) {
            $this->requestError = true;
            $this->errors[] = [
                'value' => $this->int,
                'valueError' => 'The between int action requires two ints, ex: 10,60::BT. This between action only utilizes the first two array items if more are passed in. This parameter was not set.',
            ];;
        }
    }

    private function setErrorsIfAny(): void
    {
        if ($this->errors) {
            $this->validatorDataCollector->setRejectedParameters([
                "$this->columnName" => [
                    'intCoveredTo' => $this->int,
                    'originalIntString' => $this->originalInt,
                    'comparisonOperatorCoveredTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                    'parameterError' => $this->errors,
                ]
            ]);
        }
    }

    private function setAcceptedParameterIfAny(): void
    {
        if (!$this->requestError) {
            $this->validatorDataCollector->setAcceptedParameters([
                "$this->columnName" => [
                    'intCoveredTo' => $this->int,
                    'originalIntString' => $this->originalInt,
                    'comparisonOperatorCoveredTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }

    private function setDataQueryArgumentIfAny(): void
    {
        if (!$this->requestError) {
            $this->validatorDataCollector->setQueryArgument([
                "$this->columnName" => [
                    'dataType' => 'int',
                    'columnName' => $this->columnName,
                    'int' => $this->int,
                    'comparisonOperator' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }
}