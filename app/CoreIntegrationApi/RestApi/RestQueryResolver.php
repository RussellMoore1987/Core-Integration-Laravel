<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\QueryResolver;

class RestQueryResolver extends QueryResolver
{

    // uses serves a provider for dependency injection, Located app\Providers\RestRequestProcessorProvider.php

    public function resolve($validatedMetaData)
    {
        dd($validatedMetaData);

        // get column data

        // TODO: get form data

        // index
        $query = $query === NULL && $validatedMetaData->action == 'INDEX' ? $this->queryIndex->get() : NULL;

        // GET
        $query = $query === NULL && $validatedMetaData->action == 'GET' ? $this->queryAssembler->query($validatedMetaData) : NULL;
        
        // persist - POST = add, PUT = update, PATCH = copy
        $query = $query === NULL && in_array($validatedMetaData->action, ['POST', 'PUT', 'PATCH']) ? $this->queryPersister->persist($validatedMetaData) : NULL;

        // delete
        $query = $query === NULL && $validatedMetaData->action == 'DELETE' ? $this->queryDeleter->delete($validatedMetaData) : NULL;

        return $query;
    }
}