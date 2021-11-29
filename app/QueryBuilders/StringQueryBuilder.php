<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
class StringQueryBuilder extends QueryBuilder {

    private ?string $operator;

    /**
     * Parses a comma sparated string into an array of values
     * @param string
     * @return array
     */
    public function parse(string $string): array
    {
        $cleaned_string = $this->stripSpacesFromString($string);
        $this->splitStringIntoArrayByCommaSeparator($cleaned_string);

        return $this->values;
    }

    // TODO: If a string has legit whitespace this could cause unintended logic errors? Leave whitespaces?
    private function stripSpacesFromString($string)
    {
        return str_replace(" ", "", $string);
    }

    private function splitStringIntoArrayByCommaSeparator($string)
    {
        $this->values = explode(',', $string);
    }

    /**
     * Builds an eloquent query builder for searching string columns
     * @param string
     * @return Builder
     */
    public function build(): Builder
    {
        $this->createBuilder();
        $this->buildQuery();

        return $this->builder;
    }

    private function createBuilder()
    {
        $this->builder = $this->model::query();
    }

    private function buildQuery()
    {
        foreach($this->values as $value) {
            $this->determineSqlComparisonOperator($value);
            $this->addWhereClause($value);
        }
    }

    private function determineSqlComparisonOperator($value)
    {
        $this->operator = $this->stringContainsPercentSymbol($value) ? 'like' : '=';
    }

    private function addWhereClause($value)
    {
        $this->builder->orWhere($this->column, $this->operator, $value);
    }

    private function stringContainsPercentSymbol($string)
    {
        return str_contains($string, '%');
    }
}
