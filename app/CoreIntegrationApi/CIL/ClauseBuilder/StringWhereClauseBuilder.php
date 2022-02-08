<?php

namespace App\CoreIntegrationApi\CIL\ClauseBuilder;

use App\Exceptions\ClauseBuilderException;
use Illuminate\Database\Eloquent\Builder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\ClauseBuilder;

class StringWhereClauseBuilder implements ClauseBuilder
{
    private $queryBuilder;
    private $column;
    private $rawString;
    private $rawStringValues;

    private $operator;
    private $bindings = [];

    public const EXACT_MATCH_SYMBOL = '::exact';

    public function build(Builder $queryBuilder, $data) : Builder
    {
        $this->setQueryBuilder($queryBuilder);
        $this->setData($data);
        $this->validateData();

        $this->parseString();
        $this->buildQuery();

        return $this->queryBuilder;
    }

    private function setQueryBuilder(Builder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    private function setData($data)
    {
        $this->column = $data['columnName'] ?? null;
        $this->rawString = $data['string'] ?? null;
    }

    private function validateData()
    {
        $this->validateColumnName();
        $this->validateString();
    }

    private function validateColumnName()
    {
        if(!isset($this->column)) {
            throw new ClauseBuilderException(ClauseBuilderException::COLUMN_NAME_NOT_SET_ERROR_MESSAGE);
        }
    }

    private function validateString()
    {
        if(!isset($this->rawString)) {
            throw new ClauseBuilderException(ClauseBuilderException::STRING_NOT_SET_ERROR_MESSAGE);
        }
    }

    // --------- Parsing the String -------------
    private function parseString(): array
    {
        $this->splitStringIntoArrayByCommaSeparator();
        $this->prepareBindings();

        return $this->bindings;
    }

    private function splitStringIntoArrayByCommaSeparator()
    {
        $this->rawStringValues = explode(',', $this->rawString);
    }

    private function prepareBindings() {
        foreach($this->rawStringValues as $rawString) {
            $rawLowerCaseString = $this->convertStringToLowerCase($rawString);
            $this->determineBinding($rawLowerCaseString);
        }
    }

    private function convertStringToLowerCase(string $string) {
        return strtolower($string);
    }

    private function determineBinding(string $string) {
        if($this->stringContainsExactMatchSymbol($string)) {
            $this->addExactMatchBinding($string);
        } else {
            $this->addLikeBinding($string);
        }
    }

    private function addExactMatchBinding(string $string) {
        $this->bindings[] = $this->stripExactMatchSymbolFromString($string);
    }

    private function addLikeBinding(string $string) {
        $this->bindings[] = $this->addPercentSymbolsToString($string);
    }

    private function stringContainsExactMatchSymbol(string $string) {
        return str_contains($string, self::EXACT_MATCH_SYMBOL);
    }

    private function stripExactMatchSymbolFromString(string $string) {
        return str_replace(self::EXACT_MATCH_SYMBOL, '', $string);
    }

    private function addPercentSymbolsToString(string $string) {
        return "%" . $string . "%";
    }

    // -------- Building the Query ---------
    private function buildQuery()
    {
        foreach($this->bindings as $binding) {
            $this->determineSqlComparisonOperator($binding);
            $this->addWhereClause($binding);
        }
    }

    private function determineSqlComparisonOperator($binding)
    {
        $this->operator = $this->stringContainsPercentSymbol($binding) ? 'like' : '=';
    }

    private function addWhereClause($binding)
    {
        $this->queryBuilder->orWhereRaw("lower(`$this->column`) $this->operator ?", $binding);
    }

    private function stringContainsPercentSymbol($string)
    {
        return str_contains($string, '%');
    }
}
