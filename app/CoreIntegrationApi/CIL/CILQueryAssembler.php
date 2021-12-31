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

        // add includes

        // loop over column arguments
            // $clauseBuilder = $this->clauseBuilderFactory->getClauseBuilder($type);
            // $this->queryBuilder = $clauseBuilder->build($this->queryBuilder, $column, $value)
        
        // return query;
        // return $this->queryBuilder->get();
    }
}