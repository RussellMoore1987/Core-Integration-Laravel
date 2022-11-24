<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\QueryResolver;

class RestQueryResolver extends QueryResolver
{
    // uses serves a provider for dependency injection, Located app\Providers\RestRequestProcessorProvider.php

    public function resolve($validatedMetaData)
    {
        $httpMethodQueryResolver = $this->httpMethodQueryResolverFactory->getFactoryItem($validatedMetaData['endpointData']['httpMethod']);
        $queryResult = $httpMethodQueryResolver->resolveQuery($validatedMetaData);

        return $queryResult;
    }
}

// TODO: make it so you can send in snake_case or camelCase as a parameter
// TODO: make constant casing on api output snake_case or camelCase