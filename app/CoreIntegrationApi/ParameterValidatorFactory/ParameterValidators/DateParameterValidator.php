<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Hamcrest\Arrays\IsArray;

class DateParameterValidator implements ParameterValidator
{
    private $columnName;
    private $date;
    private $dateAction;
    private $comparisonOperator;
    private $errors;

    // TODO: make sure to clean up all classes associated with these changes as well as the ***URL diagram***

    public function validate(ValidatorDataCollector $validatorDataCollector, $parameterData) : ValidatorDataCollector
    {
        $this->setMainVariables($validatorDataCollector, $parameterData);
        $this->processDateData();
        $this->checkForErrors();
        $this->setErrorsIfAny();
        $this->setAcceptedParameter(); 
        $this->setDataCollectorWithQueryArgument(); 

        return $this->validatorDataCollector;
    }

    private function setMainVariables($validatorDataCollector, $parameterData)
    {
        $this->validatorDataCollector = $validatorDataCollector;

        foreach ($parameterData as $columnName => $date) { // we should only have one array item
            $this->columnName = $columnName;
            $this->date = $date;
            $this->originalDate = $date;
        } 
    }

    private function processDateData()
    {
        $this->processDateString();
        $this->setComparisonOperator();
    }

    private function processDateString()
    {
        if (str_contains($this->date, '::')) {
            $date_array = explode('::', $this->date);
    
            $this->dateAction = strtolower($date_array[1]);
    
            if (str_contains($date_array[0], ',') && $this->dateAction == 'bt') {
                $between_dates = explode(',', $date_array[0]);
                $this->date = [];
                $this->date[] = $this->convertToDate($between_dates[0]);
                $this->date[] = date('Y-m-d H:i:s', strtotime("tomorrow", strtotime($between_dates[1])) - 1); // End of day - default 1970-01-01 23:59:59
            } else {
                $this->date = $this->convertToDate($date_array[0]);
            }

        } else {
            $this->date = $this->convertToDate($this->date);
        }
    }

    private function convertToDate($dateString)
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
    }

    private function validateBetweenDatesAreCorrect()
    {
        $this->checkToSeeIfFirstDateIsGreaterThenLastDate();
        $this->checkToSeeIfWeHaveToDates();
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

    private function checkToSeeIfWeHaveToDates()
    {
        if (
            $this->comparisonOperator == 'bt' && 
            !(is_array($this->date) && 
            count($this->date) == 2)
        ) {
            $this->error = true;
            $this->errors[] = "The between date action requires two dates, no more, no less. ex: 2021-01-01,2021-12-31::BT.";
        }
    }

    private function setErrorsIfAny()
    {
        if ($this->errors) {
            $this->validatorDataCollector->setRejectedParameter([
                "$this->columnName" => [
                    'dateCoveredTo' => $this->date,
                    'originalDate' => $this->originalDate,
                    'comparisonOperator' => $this->comparisonOperator,
                    'parameterError' => $this->errors,
                ]
            ]);
        }
    }

    private function setAcceptedParameter()
    {
        if (!$this->errors) {
            $this->validatorDataCollector->setAcceptedParameter([
                "$this->columnName" => [
                    'dateCoveredTo' => $this->date,
                    'originalDate' => $this->originalDate,
                    'comparisonOperator' => $this->comparisonOperator,
                ]
            ]);
        }
    }

    private function setDataCollectorWithQueryArgument()
    {
        if (!$this->errors) {
            $this->validatorDataCollector->setQueryArgument([
                'dataType' => 'date',
                'columnName' => $this->columnName,
                'date' => $this->date,
                'comparisonOperator' => $this->comparisonOperator,
            ]);
        }
    }
}