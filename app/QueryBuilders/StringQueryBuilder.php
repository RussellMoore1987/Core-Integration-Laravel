<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\EmptyBindingsException;
use App\Exceptions\ColumnNotSetException;
use App\Exceptions\ModelNotSetException;
class StringQueryBuilder extends QueryBuilder {

    private ?string $operator;

    private array $rawStringValues = [];

    public const EXACT_MATCH_SYMBOL = '::exact';

    /**
     * Parses a comma sparated string into an array of bindings for the query
     * @param string
     * @return array
     */
    public function parse(string $string): array
    {
        $this->splitStringIntoArrayByCommaSeparator($string);
        $this->prepareBindings();

        return $this->bindings;
    }

    private function splitStringIntoArrayByCommaSeparator($string)
    {
        $this->rawStringValues = explode(',', $string);
    }

    private function prepareBindings() {
        foreach($this->rawStringValues as $value) {
            $this->determineBinding($value);
        }
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

    /**
     * Builds an eloquent query builder for searching string columns
     * @param string
     * @return Builder
     */
    public function build(): Builder
    {
        $this->validateQueryPrerequisites();
        $this->createBuilder();
        $this->buildQuery();

        return $this->builder;
    }

    private function validateQueryPrerequisites()
    {
        $this->validateBindings();
        $this->validateColumn();
        $this->validateModel();
    }

    private function validateBindings()
    {
        if(empty($this->bindings)) {
            throw new EmptyBindingsException("Must have bindings set before building the query!");
        }
    }

    private function validateColumn()
    {
        if(!isset($this->column)) {
            throw new ColumnNotSetException("Must have a column set before building the query!");
        }
    }

    private function validateModel()
    {
        if(!isset($this->model)) {
            throw new ModelNotSetException("Must have a model set before building the query!");
        }
    }

    private function createBuilder()
    {
        $this->builder = $this->model::query();
    }

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
        $this->builder->orWhere($this->column, $this->operator, $binding);
    }

    private function stringContainsPercentSymbol($string)
    {
        return str_contains($string, '%');
    }
}
