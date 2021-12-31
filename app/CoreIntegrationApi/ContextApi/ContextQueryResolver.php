<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\QueryResolver;

class ContextQueryResolver extends QueryResolver
{
    // uses serves provider Located ...
    // loads function __construct(CILQueryAssembler $queryAssembler, CILQueryPersister $queryPersister, ContextQueryIndex $queryIndex, CILQueryDeleter $queryDeleter)

    public function resolve($validatedQueryData)
    {
        $queries = [];

        foreach ($validatedQueryData as $queryArguments) {

            // bad endpoint
            $query = $queryArguments->errors ? $queryArguments->errors : NULL;

            // get column data

            // get form data

            // index
            $query = $query === NULL && $queryArguments->action == 'index' ? $this->queryIndex->get() : NULL;

            // GET
            $query = $query === NULL && $queryArguments->action == 'get' ? $this->queryAssembler->query($queryArguments) : NULL;
            
            // persist save = add/update
            $query = $query === NULL && in_array($queryArguments->action, ['save', 'copy']) ? $this->queryPersister->persist($queryArguments) : NULL;

            // delete
            $query = $query === NULL && $queryArguments->action == 'delete' ? $this->queryDeleter->delete($queryArguments) : NULL;

            $queries[] = $query;
        }
        
        return $queries;
    }
}