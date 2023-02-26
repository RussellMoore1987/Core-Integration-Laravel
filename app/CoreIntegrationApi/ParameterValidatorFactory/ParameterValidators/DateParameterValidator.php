<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class DateParameterValidator implements ParameterValidator
{
    private $validatorDataCollector;
    private $parameterName;
    private $date;
    private $originalDate;
    private $dateAction;
    private $comparisonOperator;
    private $originalComparisonOperator = '';
    private $errors;

    public function validate(string $parameterName, string $parameterValue, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->parameterName = $parameterName;
        $this->date = $parameterValue;
        $this->originalDate = $parameterValue;

        $this->processDateData();
        $this->checkForErrors();
        $this->setAcceptedParameterIfAny();
        $this->setDataQueryArgumentIfAny();
    }

    private function processDateData()
    {
        $this->processDateString();
        $this->setComparisonOperator();
    }

    private function processDateString()
    {
        if (str_contains($this->date, '::')) {
            $dateArray = explode('::', $this->date);
    
            $this->originalComparisonOperator = $dateArray[1];
            $this->dateAction = strtolower($dateArray[1]);
    
            if (str_contains($dateArray[0], ',') && in_array($this->dateAction, ['between', 'bt'])) {
                $between_dates = explode(',', $dateArray[0]);
                $this->date = [];
                $this->date[] = $this->convertStringToDate($between_dates[0]);
                $this->date[] = date('Y-m-d H:i:s', strtotime("tomorrow", strtotime($between_dates[1])) - 1); // End of day
            } else {
                $this->date = $this->convertStringToDate($dateArray[0]);
            }

        } else {
            $this->date = $this->convertStringToDate($this->date);
        }
    }

    private function convertStringToDate($dateString)
    {
        return date('Y-m-d H:i:s', strtotime($dateString));
    }

    private function setComparisonOperator()
    {
        if (in_array($this->dateAction, ['greaterthan', 'gt', '>'])) {
            $this->comparisonOperator = '>';
        } elseif (in_array($this->dateAction, ['greaterthanorequal', 'gte', '>='])) {
            $this->comparisonOperator = '>=';
        } elseif (in_array($this->dateAction, ['lessthan', 'lt', '<'])) {
            $this->comparisonOperator = '<';
        } elseif (in_array($this->dateAction, ['lessthanorequal', 'lte', '<='])) {
            $this->comparisonOperator = '<=';
        } elseif (in_array($this->dateAction, ['between', 'bt'])) {
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
            $this->validatorDataCollector->setRejectedParameters([
                "$this->parameterName" => [
                    'dateCoveredTo' => $this->date,
                    'originalDate' => $this->originalDate,
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
            $this->validatorDataCollector->setAcceptedParameters([
                "$this->parameterName" => [
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
                "$this->parameterName" => [
                    'dataType' => 'date',
                    'columnName' => $this->parameterName,
                    'date' => $this->date,
                    'comparisonOperator' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }
}