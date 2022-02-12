<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\QueryResolver;

class RestQueryResolver extends QueryResolver
{

    // uses serves provider Located app\Providers\RestRequestProcessorProvider.php

    public function resolve($validatedQueryData)
    {
        // bad endpoint / errors
        $query = $validatedQueryData->errors ? $validatedQueryData->errors : NULL;

        // get column data

        // get form data

        // index
        $query = $query === NULL && $validatedQueryData->action == 'INDEX' ? $this->queryIndex->get() : NULL;

        // GET
        $query = $query === NULL && $validatedQueryData->action == 'GET' ? $this->queryAssembler->query($validatedQueryData) : NULL;
        
        // persist - POST = add, PUT = update, PATCH = copy
        $query = $query === NULL && in_array($validatedQueryData->action, ['POST', 'PUT', 'PATCH']) ? $this->queryPersister->persist($validatedQueryData) : NULL;

        // delete
        $query = $query === NULL && $validatedQueryData->action == 'DELETE' ? $this->queryDeleter->delete($validatedQueryData) : NULL;

        return $query;
    }
}