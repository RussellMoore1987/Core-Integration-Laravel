<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ComparisonOperatorProvider;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ActionFinder;
use App\CoreIntegrationApi\ValidatorDataCollector;

// TODO: read over file and test readability, test coverage, test organization, tests grouping, go one by one
// TODO: split processing int / array into own class??? it is getting big and hard to know if I'm covering everything in tests***
// TODO: Perhaps remove multiple options and just force them to use 1 [bt] not [bt,between], ect...

class IntParameterValidator implements ParameterValidator
{
    protected $comparisonOperatorProvider;
    protected $errorCollector;
    protected $actionFinder;
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

    public function __construct(ComparisonOperatorProvider $comparisonOperatorProvider, ErrorCollector $errorCollector, ActionFinder $actionFinder)
    {
        $this->comparisonOperatorProvider = $comparisonOperatorProvider;
        $this->errorCollector = $errorCollector;
        $this->actionFinder = $actionFinder;
    }

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
        [$this->int, $this->intAction, $this->originalComparisonOperator] = $this->actionFinder->parseValue($this->int, $this->errorCollector);
        $this->isArrayThenProcessArray();
        $this->isNotArrayThenProcessAsSingleInt();
    }
    
    protected function isArrayThenProcessArray(): void
    {
        // if isArray
        // $this->int = $this->intArrayProcessor->process($this->int, $this->comparisonOperator, $this->errorCollector);
        // if $this->intAction = '' then it is a single int
        if ($this->shouldProcessAsArray()) {
            $this->processedAsArray = true;
            $this->evaluateEachIntInArray();
            $this->isIntArrayValidThenSetElseSetError();
        }
    }

    protected function shouldProcessAsArray(): bool
    {
        return str_contains($this->int, ',') && (
            in_array($this->intAction, ['between', 'bt', 'in', 'notin']) ||
            $this->intAction === null
        );
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
                $this->errorCollector->add([
                    'value' => (float) $value,
                    'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a float.",
                ]);
            } else {
                $this->errorCollector->add([
                    'value' => $value,
                    'valueError' => "The value at the index of {$index} is not an int. Only ints are permitted for this parameter. Your value is a string.",
                ]);
            }
        }
    }

    protected function isIntArrayValidThenSetElseSetError(): void
    {
        if ($this->realInts) {
            $this->int = $this->realInts;
        } else {
            $this->errorCollector->add([
                'value' => $this->int,
                'valueError' => 'There are no ints available in this array.',
            ]);
        }
    }

    protected function isNotArrayThenProcessAsSingleInt(): void
    {
        // TODO: make a class for this
        // if int
        // $this->int = $this->intProcessor->process($this->int, $this->errorCollector);
        if (!is_array($this->int) && !$this->processedAsArray) {
            if ($this->isInt($this->int)) {
                $this->int = (int) $this->int;
            } elseif (is_numeric($this->int)) {
                $this->errorCollector->add([
                    'value' => (float) $this->int,
                    'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a float.',
                ]);
            } elseif (str_contains($this->int, ',')) {
                $this->errorCollector->add([
                    'value' => $this->int,
                    'valueError' => 'Unable to process array of ints. You must use one of the accepted comparison operator such as "between", "bt", "in", or "notin" to process an array.',
                ]);
            } else {
                $this->errorCollector->add([
                    'value' => $this->int,
                    'valueError' => 'The value passed in is not an int. Only ints are permitted for this parameter. Your value is a string.',
                ]);
            }
        }
    }

    protected function isInt($value): bool
    {
        return is_numeric($value) && !str_contains($value, '.');
    }

    protected function setComparisonOperator(): void
    {
        $this->intAction = $this->intAction ?? '=';

        if ($this->intAction != 'inconclusive') {
            $this->comparisonOperator = $this->comparisonOperatorProvider->select($this->intAction, $this->errorCollector);
        } else {
            $this->comparisonOperator = null;
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
            $this->errorCollector->add([
                'value' => $this->int,
                'valueError' => 'The First int must be smaller than the second int, ex: 10,60::BT.',
            ]);
        }
    }

    protected function isIntAnArrayAndCountEqualToTwoThenThrowError(): void
    {
        if (!(is_array($this->int) && count($this->int) == 2)) {
            $this->errorCollector->add([
                'value' => $this->int,
                'valueError' => 'The between int action requires two ints, ex: 10,60::BT.',
            ]);
        }
    }

    protected function setErrorsIfAny(): void
    {
        if ($this->errorCollector->getErrors()) {
            $this->validatorDataCollector->setRejectedParameters([
                "$this->parameterName" => [
                    'intConvertedTo' => $this->int,
                    'originalIntString' => $this->originalInt,
                    'comparisonOperatorConvertedTo' => $this->comparisonOperator,
                    'originalComparisonOperator' => $this->originalComparisonOperator,
                    'parameterError' => $this->errorCollector->getErrors(),
                ]
            ]);
        }
    }

    protected function setAcceptedParameterIfNoErrors(): void
    {
        if (!$this->errorCollector->getErrors()) {
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
        if (!$this->errorCollector->getErrors()) {
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
