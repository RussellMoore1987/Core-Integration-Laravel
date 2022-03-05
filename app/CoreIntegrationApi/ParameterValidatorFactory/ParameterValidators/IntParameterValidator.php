<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class IntParameterValidator implements ParameterValidator
{
    private $columnName;
    private $int;
    private $originalInt;
    private $intAction;
    private $comparisonOperator;
    private $originalComparisonOperator = '';
    private $errors;
    private $processedAsArray = false;
    private $requestError = false;

    public function validate(ValidatorDataCollector $validatorDataCollector, $parameterData) : ValidatorDataCollector
    {
        $this->setMainVariables($validatorDataCollector, $parameterData);
        $this->processData();
        $this->checkForOtherErrors();
        $this->setErrorsIfAny();
        $this->setAcceptedParameterIfAny(); 
        $this->setDataQueryArgumentIfAny(); 

        return $this->validatorDataCollector;
    }

    private function setMainVariables($validatorDataCollector, $parameterData)
    {
        $this->validatorDataCollector = $validatorDataCollector;

        foreach ($parameterData as $columnName => $int) { // we should only have one array item
            $this->columnName = $columnName;
            $this->int = $int;
            $this->originalInt = $int;
        } 
    }

    private function processData()
    {
        $this->processIntString();
        $this->setComparisonOperator();
    }

    private function processIntString()
    {
        $this->seeIfParameterHasAction();
        $this->seeIfParameterHasArrayProcessAccordingly();
    }

    private function seeIfParameterHasAction()
    {
        if (str_contains($this->int, '::')) {
            $int_array = explode('::', $this->int);
    
            $this->originalComparisonOperator = $int_array[1];
            $this->intAction = strtolower($int_array[1]);
            $this->int = $int_array[0];
        } 
    }

    private function seeIfParameterHasArrayProcessAccordingly()
    {
        $this->IfArray();
        $this->IfNotArray();
    }

    private function IfArray()
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

    private function isInt($value)
    {
        return is_numeric($value) && !str_contains($value, '.');
    }

    private function IfNotArray()
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

    private function setComparisonOperator()
    {
        if (in_array($this->intAction, ['greaterthan', 'gt', '>'])) {
            $this->comparisonOperator = '>';
        } else if (in_array($this->intAction, ['greaterthanorequal', 'gte', '>='])) {
            $this->comparisonOperator = '>=';
        } else if (in_array($this->intAction, ['lessthan', 'lt', '<'])) {
            $this->comparisonOperator = '<';
        } else if (in_array($this->intAction, ['lessthanorequal', 'lte', '<='])) {
            $this->comparisonOperator = '<=';
        } else if (in_array($this->intAction, ['between', 'bt'])) {
            $this->comparisonOperator = 'bt';
        } else if ($this->intAction == 'in') {
            $this->comparisonOperator = 'in';
        } else if ($this->intAction == 'notin') {
            $this->comparisonOperator = 'notin';
        } else {
            $this->comparisonOperator = '=';
        }
    }

    private function checkForOtherErrors()
    {
        $this->validateBetweenIntsAreCorrect();
    }

    private function validateBetweenIntsAreCorrect()
    {
        $this->checkToSeeIfFirstIntIsGreaterThenLastInt();
        $this->checkToSeeIfWeHaveTwoInts();
    }

    private function checkToSeeIfFirstIntIsGreaterThenLastInt()
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

    private function checkToSeeIfWeHaveTwoInts()
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

    private function setErrorsIfAny()
    {
        if ($this->errors) {
            $this->validatorDataCollector->setRejectedParameter([
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

    private function setAcceptedParameterIfAny()
    {
        if (!$this->requestError) {
            $this->validatorDataCollector->setAcceptedParameter([
                "$this->columnName" => [
                    'intCoveredTo' => $this->int,
                    'originalIntString' => $this->originalInt,
                    'comparisonOperatorCoveredTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }

    private function setDataQueryArgumentIfAny()
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