<?php

namespace App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\HttpMethodQueryResolver;

class PostHttpMethodQueryResolver implements HttpMethodQueryResolver
{
    public function resolveQuery($validatedMetaData)
    {
        // TODO: need to refactor proses soon
        $class = new $validatedMetaData['endpointData']['class'];
        foreach ($validatedMetaData['queryArguments'] as $property => $value) {
            $class->$property = $value;
        }
        $class->save(); // TODO: fix date format 2/2/1979 to 1979-02-02
        return $class; 
        // // dd($class, $validatedMetaData);
        // $redirect = false;
        // $class->validateAndSave($validatedMetaData['queryArguments']);
    }
}