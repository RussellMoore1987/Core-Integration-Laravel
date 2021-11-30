<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\EmptyBindingsException;
use App\Exceptions\ColumnNotSetException;
use App\Exceptions\ModelNotSetException;
class StringQueryBuilder extends QueryBuilder {

    private ?string $operator;

    /**
     * Parses a comma sparated string into an array of bindings for the query
     * @param string
     * @return array
     */
    public function parse(string $string): array
    {
        $cleaned_string = $this->stripSpacesFromString($string);
        $this->splitStringIntoArrayByCommaSeparator($cleaned_string);

        return $this->bindings;
    }

    // TODO: If a string has legit whitespace this could cause unintended logic errors? Leave whitespaces?
    private function stripSpacesFromString($string)
    {
        return str_replace(" ", "", $string);
    }

    private function splitStringIntoArrayByCommaSeparator($string)
    {
        $this->bindings = explode(',', $string);
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
