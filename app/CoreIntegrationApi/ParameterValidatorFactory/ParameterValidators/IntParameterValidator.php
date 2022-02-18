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

    public function validate(ValidatorDataCollector $validatorDataCollector, $parameterData) : ValidatorDataCollector
    {
        $this->setMainVariables($validatorDataCollector, $parameterData);
        $this->processData();
        $this->checkForErrors();
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
        if (str_contains($this->int, '::')) {
            $int_array = explode('::', $this->int);
    
            $this->originalComparisonOperator = $int_array[1];
            $this->intAction = strtolower($int_array[1]);
            $this->int = $int_array[0];
        } 
        
        // ! working here ****************************************************
        if (str_contains($this->int, ',') && in_array($this->intAction, ['between', 'bt', 'in', 'notin'])) {
            $ints = explode(',', $this->int);
            foreach ($ints as $index => $value) {
                if (is_numeric($value) && is_int((int)$value)) {
                    $realInts[] = (int) $value;
                } elseif (is_numeric($value)) {
                    $this->errors[] = [
                        'int' => (float)$value,
                        'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your vale is a float.",
                    ];
                } else {
                    $this->errors[] = [
                        'int' => $value,
                        'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your vale is a string.",
                    ];
                }
            }
        } 
        
        
        else {
            $this->date = $this->convertStringToDate($this->date);
        }
    }

    private function convertStringToDate($dateString)
    {
        return date('Y-m-d H:i:s', strtotime($dateString));
    }

    private function setComparisonOperator()
    {
        if (in_array($this->dateAction, ['greaterthan', 'gt'])) {
            $this->comparisonOperator = '>';
        } else if (in_array($this->dateAction, ['greaterthanorequal', 'gte'])) {
            $this->comparisonOperator = '>=';
        } else if (in_array($this->dateAction, ['lessthan', 'lt'])) {
            $this->comparisonOperator = '<';
        } else if (in_array($this->dateAction, ['lessthanorequal', 'lte'])) {
            $this->comparisonOperator = '<=';
        } else if (in_array($this->dateAction, ['between', 'bt'])) {
            $this->comparisonOperator = 'bt';
        } else {
            $this->comparisonOperator = '=';
        }
    }

    private function checkForErrors()
    {
        $this->validateBetweenDatesAreCorrect();
        $this->setErrorsIfAny();
    }

    private function validateBetweenDatesAreCorrect()
    {
        $this->checkToSeeIfFirstDateIsGreaterThenLastDate();
        $this->checkToSeeIfWeHaveTwoDates();
    }

    private function checkToSeeIfFirstDateIsGreaterThenLastDate()
    {
        if (
            $this->comparisonOperator == 'bt' && 
            is_array($this->date) && 
            $this->date[0] > $this->date[1]
        ) {
            $this->error = true;

            $firstDate = $this->date[0];
            $secondDate = $this->date[1];
            $this->errors[] = "The first date \"{$firstDate}\" must be smaller than the last date \"{$secondDate}\" sent in.";
        }
    }

    private function checkToSeeIfWeHaveTwoDates()
    {
        if (
            $this->comparisonOperator == 'bt' && 
            !(
                is_array($this->date) && 
                count($this->date) == 2
            )
        ) {
            $this->error = true;
            $this->errors[] = "The between date action requires two dates, ex: 2021-01-01,2021-12-31::BT. It only utilizes the first two if more are passed in.";
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
        if (!$this->errors) {
            $this->validatorDataCollector->setAcceptedParameter([
                "$this->columnName" => [
                    'dateCoveredTo' => $this->date,
                    'originalDate' => $this->originalDate,
                    'comparisonOperatorCoveredTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }

    private function setDataQueryArgumentIfAny()
    {
        if (!$this->errors) {
            $this->validatorDataCollector->setQueryArgument([
                'dataType' => 'date',
                'columnName' => $this->columnName,
                'date' => $this->date,
                'comparisonOperator' => $this->comparisonOperator,
                'originalComparisonOperator' => $this->originalComparisonOperator,
            ]);
        }
    }
}