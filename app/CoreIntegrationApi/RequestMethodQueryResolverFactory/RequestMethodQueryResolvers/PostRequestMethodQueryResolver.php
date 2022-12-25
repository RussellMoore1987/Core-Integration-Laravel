<?php

namespace App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\RequestMethodQueryResolver;

class PostRequestMethodQueryResolver implements RequestMethodQueryResolver
{
    public function resolveQuery($validatedMetaData)
    {
        // TODO: need to refactor proses soon
        $class = new $validatedMetaData['resourceInfo']['path'];
        foreach ($validatedMetaData['queryArguments'] as $property => $value) {
            $class->$property = $value;
        }
        $class->save(); // TODO: fix date format 2/2/1979 to 1979-02-02

        // send back full new resource
        $resourcePrimaryKey = $class->getKeyName(); 
        $class = $validatedMetaData['resourceInfo']['path']::find($class->$resourcePrimaryKey);
        return $class; 
        // // dd($class, $validatedMetaData);
        // $redirect = false;
        // $class->validateAndSave($validatedMetaData['queryArguments']);
    }
}