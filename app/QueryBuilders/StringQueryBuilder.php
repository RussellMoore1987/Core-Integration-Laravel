<?php

namespace App\QueryBuilders;

class StringQueryBuilder extends QueryBuilder {

    private $values = [];
    private $model = null;
    private $builder;

    public function setModel($model)
    {
        $this->model = $model;
    }

    // TODO: Should this be implementation or exposed method?
    // TODO: Should the parsing belong to another class?
    public function parse($string): array
    {
        $cleaned_string = $this->stripSpacesFromString($string);
        $this->separateStringByComma($cleaned_string);

        return $this->values;
    }

    private function stripSpacesFromString($string)
    {
        return str_replace(" ", "", $string);
    }

    private function separateStringByComma($string)
    {
        $this->values = explode(',', $string);
    }

    public function build($column)
    {
        $this->createbuilder();
        $this->buildQuery($column);

        return $this->builder;
    }

    private function createBuilder()
    {
        $this->builder = $this->model::query();
    }

    private function buildQuery($column)
    {
        foreach($this->values as $value) {
            // TODO: Get rid of these function params and return values
            $operator = $this->determineOperator($value);
            $this->addWhereClause($column, $operator, $value);
        }
    }

    private function determineOperator($value)
    {
        return $this->stringContainsPercentSymbol($value) ? 'like' : '=';
    }

    private function addWhereClause($column, $operator, $value)
    {
        $this->builder->orWhere($column, $operator, $value);
    }

    private function stringContainsPercentSymbol($string)
    {
        return str_contains($string, '%');
    }
}
