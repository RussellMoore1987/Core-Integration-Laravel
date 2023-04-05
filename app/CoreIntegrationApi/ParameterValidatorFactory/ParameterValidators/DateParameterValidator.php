<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;

class DateParameterValidator implements ParameterValidator
{
    protected $validatorDataCollector;
    protected $parameterName;
    protected $date;
    protected $originalDate;
    protected $originalComparisonOperator = '';
    protected $dateAction;
    protected $comparisonOperator;
    protected $errors;

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

    protected function processDateData()
    {
        $this->processDateString();
        $this->setComparisonOperator();
    }

    protected function processDateString()
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

    protected function convertStringToDate($dateString)
    {
        return date('Y-m-d H:i:s', strtotime($dateString));
    }

    protected function setComparisonOperator()
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

    protected function checkForErrors()
    {
        $this->validateBetweenDatesAreCorrect();
        $this->setErrorsIfAny();
    }

    protected function validateBetweenDatesAreCorrect()
    {
        $this->checkToSeeIfFirstDateIsGreaterThenLastDate();
        $this->checkToSeeIfWeHaveTwoDates();
    }

    protected function checkToSeeIfFirstDateIsGreaterThenLastDate()
    {
        if (
            $this->comparisonOperator == 'bt' &&
            is_array($this->date) &&
            $this->date[0] > $this->date[1]
        ) {
            $firstDate = $this->date[0];
            $secondDate = $this->date[1];
            $this->errors[] = "The first date \"{$firstDate}\" must be smaller than the last date \"{$secondDate}\" sent in.";
        }
    }

    protected function checkToSeeIfWeHaveTwoDates()
    {
        if (
            $this->comparisonOperator == 'bt' &&
            !(
                is_array($this->date) &&
                count($this->date) == 2
            )
        ) {
            $this->errors[] = "The between date action requires two dates, ex: 2021-01-01,2021-12-31::BT. It only utilizes the first two if more are passed in.";
        }
    }

    protected function setErrorsIfAny()
    {
        if ($this->errors) {
            $this->validatorDataCollector->setRejectedParameters([
                "$this->parameterName" => [
                    'dateConvertedTo' => $this->date,
                    'originalDate' => $this->originalDate,
                    'comparisonOperatorConvertedTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                    'parameterError' => $this->errors,
                ]
            ]);
        }
    }

    protected function setAcceptedParameterIfAny()
    {
        if (!$this->errors) {
            $this->validatorDataCollector->setAcceptedParameters([
                "$this->parameterName" => [
                    'dateConvertedTo' => $this->date,
                    'originalDate' => $this->originalDate,
                    'comparisonOperatorConvertedTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                ]
            ]);
        }
    }

    protected function setDataQueryArgumentIfAny()
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
