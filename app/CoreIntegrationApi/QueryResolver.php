<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\QueryAssembler;
use App\CoreIntegrationApi\QueryPersister;
use App\CoreIntegrationApi\QueryIndex;
use App\CoreIntegrationApi\QueryDeleter;

abstract class QueryResolver
{
    protected $queryAssembler;
    protected $queryPersister;
    protected $queryIndex;
    protected $queryDeleter;

    function __construct(QueryAssembler $queryAssembler, QueryPersister $queryPersister, QueryIndex $queryIndex, QueryDeleter $queryDeleter) 
    {
        $this->queryAssembler = $queryAssembler;
        $this->queryPersister = $queryPersister;
        $this->queryIndex = $queryIndex;
        $this->queryDeleter = $queryDeleter;
    }

    abstract public function resolve($validatedQueryData);
}