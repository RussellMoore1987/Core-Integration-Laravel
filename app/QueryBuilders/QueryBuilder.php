<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryBuilder {

    protected array $values = [];
    protected ?Builder $builder;
    protected ?string $model;
    protected ?string $column;

    /**
     * Builds an eloquent query builder
     * @param string
     * @return Builder
     */
    abstract public function build(): Builder;

    /**
     * Parses a string into an array which can be used in bindings for the query builder
     * @param string
     * @return array
     */
    abstract public function parse(string $string): array;

    /**
     * Set the model to perform the query on
     * @param string - A class name for the model
     * @return void
     */
    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    /**
     * Set the column to perform the query on
     * @param string - The name of the column to query
     * @return void
     */
    public function setColumn(string $column): void
    {
        $this->column = $column;
    }
}
