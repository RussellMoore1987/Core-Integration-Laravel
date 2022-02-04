<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class DateParameterValidator implements ParameterValidator
{
    private $columnName;
    private $date;
    private $dateAction;
    private $comparisonOperator;
    private $error;

    // TODO: make sure to clean up all classes associated with these changes as well as the URL diagram
    // TODO: add rejected classes, add accepted parameters, add check full failure

    public function validate(ValidatorDataCollector $validatorDataCollector, $parameterData) : ValidatorDataCollector
    {
        $this->setMainVariables($validatorDataCollector, $parameterData);
        $this->processDateData();
        $this->setAcceptedParameter(); 
        $this->setDataCollector(); 

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
                $this->date[] = date('Y-m-d', strtotime($between_dates[0])); // Beginning of day - default 1970-01-01
                $this->date[] = date('Y-m-d H:i:s', strtotime("tomorrow", strtotime($between_dates[1])) - 1); // End of day - default 1970-01-01 23:59:59

                $this->validateBetweenDatesAreCorrect();
            } else {
                $this->date = date('Y-m-d', strtotime($date_array[0]));
            }

        } else {
            $this->date = date('Y-m-d', strtotime($this->date));
        }
    }

    private function validateBetweenDatesAreCorrect()
    {
        if ($this->date[0] > $this->date[1]) {
            $this->error = true;
            $this->validatorDataCollector->setRejectedParameter([
                "$this->columnName" => [
                    'dateCoveredTo' => $this->date,
                    'originalDate' => $this->originalDate,
                    'comparisonOperator' => $this->comparisonOperator,
                ]
            ]);
        }
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

    private function setAcceptedParameter()
    {
        if (!$this->error) {
            $this->validatorDataCollector->setAcceptedParameter([
                "$this->columnName" => [
                    'dateCoveredTo' => $this->date,
                    'originalDate' => $this->originalDate,
                    'comparisonOperator' => $this->comparisonOperator,
                ]
            ]);
        }
    }

    private function setDataCollector()
    {
        if (!$this->error) {
            $this->validatorDataCollector->setQueryArgument([
                'dataType' => 'date',
                'columnName' => $this->columnName,
                'date' => $this->date,
                'comparisonOperator' => $this->comparisonOperator,
            ]);
        }
    }
}