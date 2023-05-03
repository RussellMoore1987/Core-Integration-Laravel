<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ComparisonOperatorProvider;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\ErrorCollector;
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
// TODO: split processing int / array into own class??? it is getting big and hard to know if I'm covering everything in tests***
// TODO: Perhaps remove multiple options and just force them to use 1 [bt] not [bt,between], ect...

class IntParameterValidator implements ParameterValidator
{
    protected $comparisonOperatorProvider;
    protected $errorCollector;
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

    // ! start here ***************************************************************** add in new classes
    // public function __construct(ComparisonOperatorProvider $comparisonOperatorProvider, ErrorCollector $errorCollector) {
    //     $this->comparisonOperatorProvider = $comparisonOperatorProvider;
    //     $this->errorCollector = $errorCollector;
    // }

    public function validate(string $parameterName, string $parameterValue, ValidatorDataCollector &$validatorDataCollector): void
    {
        $this->validatorDataCollector = $validatorDataCollector;
        $this->parameterName = $parameterName;
        $this->int = $parameterValue;
        $this->originalInt = $parameterValue;
        
        $this->processIntParameter();
        $this->setComparisonOperator();
        // $this->comparisonOperator = $comparisonOperatorProvider->select($this->intAction, $this->errorCollector);
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
                    'parameterError' => $this->errors, // TODO: errors (s) add
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

// and or???

// split
    // action
    // array
    // Individual value

// int
    // 1
    // 1,2,3,4,5,6 // default in
    // 1,2,3,4,5,6::notIn
    // 1,2,3,4,5,6::in
    // 1,2::bt
    // 1::e
    // 1::gt
    // 1::gte
    // 1::lt
    // 1::lte
// float
    // 1.2
    // 1.3,2.5,3.6,4.4 // default in
    // 1.3,2.5,3.6,4.4::notIn
    // 1.3,2.5,3.6,4.4::in
    // 1.2,2.3::bt
    // 1.3::e
    // 1.3::gt
    // 1.3::gte
    // 1.3::lt
    // 1.3::lte
// date
    // 1/1/23
    // 2023-01-01 00:00:00
    // 1/1/23,2/1/23 // default bt
    // 1/1/23,2/1/23::bt
    // today,yesterday::bt
    // 1/1/23::e
    // 1/1/23::gt
    // 1/1/23::gte
    // 1/1/23::lt
    // 1/1/23::lte
    // ability to process date string (today), date formats, date and time, dateTime number, year
// string
    // sam // default like
    // sam::e
    // sam,sammy // default or like
    // sam,sammy::and like ??? ???
    // sam::like
// json
    // ? https://laravel.com/docs/10.x/queries#json-where-clauses
    // v1
        // get
            // ???
            // team.users.lead.lead_id,123::e
            // team.users.lead.lead_id,123::>
            // team.users.lead.lead_id,123::>=
            // team.users.lead.lead_id,123::<
            // team.users.lead.lead_id,123::<=
        // post, put, patch -> all or nothing
// includes
    // ? https://laravel.com/docs/8.x/eloquent-relationships#eager-loading-specific-columns
        // ! When using this feature, you should always include the id column and any relevant foreign key columns in the list of columns you wish to retrieve.
        // Book::with('author:id,name,book_id')->get();
        // author:id,name,book_id
    // ? https://laravel.com/docs/8.x/eloquent-relationships#eager-loading-multiple-relationships // ***
        // Book::with(['author', 'publisher'])->get();
        // author,posts
        // author:id,name,book_id::posts:id,title
        // author:id,name,book_id::posts
    // ? https://laravel.com/docs/8.x/eloquent-relationships#nested-eager-loading // ***
        // Book::with('author.contacts')->get();
        // posts.author.contacts
        // author,posts.author.contacts
        // author.contacts,posts.author.contacts
        // ???
        // author:id,name,book_id.contacts::posts.author:id,name.contacts:id,name
        // author:id,name,book_id::posts:id,title
        // author:id,name,book_id::posts
    // v2
        // ? https://laravel.com/docs/8.x/eloquent-relationships#constraining-eager-loads ???
        // User::with(['posts' => function ($query) {
        //     $query->where('title', 'like', '%code%');
        // }])->get();
        // $users = User::with(['posts' => function ($query) {
        //     $query->orderBy('created_at', 'desc');
        // }])->get();
        // author||created_at,title|desc::posts:id,title // ???
// class methods
    // fullName
    // fullName,fullAddress
// method responses // ?? v2
    // resource?method=methodName
    // resource?method=methodName::data
    // bireports?method=insidesales::2023-01-01,2023-03-31
// order by (ASC|DESC)
    // id
    // id,name
    // id::desc,name::asc
// select
    // id
    // id,name