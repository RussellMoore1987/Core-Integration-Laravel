<?php

namespace App\CoreIntegrationApi\CIL;

use App\CoreIntegrationApi\QueryAssembler;
use App\CoreIntegrationApi\CIL\ClauseBuilderFactory;

class CILQueryAssembler implements QueryAssembler
{
    protected $clauseBuilderFactory;
    protected $queryBuilder;

    function __construct(ClauseBuilderFactory $clauseBuilderFactory) 
    {
        $this->clauseBuilderFactory = $clauseBuilderFactory;
    }

    public function query($validatedQueryData)
    {
        // start laravel builder
        // $this->queryBuilder

        // loop over types arguments
            // ? https://laravel.com/docs/8.x/queries#where-clauses
            // select - all in one - $data = ['name', 'email']
            // column - Individually - $data = [$column, $value]
            // includes - all in one - $data = ['tags', 'categories', 'invoices'] - $books = Book::with(['author', 'publisher'])->get(); // ? https://laravel.com/docs/8.x/eloquent-relationships
            // order by - Individually - $data = name
            // Don't know exactly how to do method calls yet
            // id - all in one - $data = 1 or 1,2,3,4,5
            // perPageParameter - set Local variable, continue, go to next item in the loop, Set perPageParameter Later in paginate($perPageParameter), default 30
                // $clauseBuilder = $this->clauseBuilderFactory->getClauseBuilder($parameterType);
                // $this->queryBuilder = $clauseBuilder->build($this->queryBuilder, $data)
        
        // return query;
        // return $this->queryBuilder->paginate($perPageParameter)
    }
}