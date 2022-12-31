<?php

namespace App\CoreIntegrationApi\RequestMethodResponseBuilderFactory;

use App\CoreIntegrationApi\RequestMethodTypeFactory;
use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\RequestMethodResponseBuilder;
use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\GetRequestMethodResponseBuilder;
use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\PostRequestMethodResponseBuilder;
use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\PutRequestMethodResponseBuilder;
use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\PatchRequestMethodResponseBuilder;
use App\CoreIntegrationApi\RequestMethodResponseBuilderFactory\RequestMethodResponseBuilders\DeleteRequestMethodResponseBuilder;

class RequestMethodResponseBuilderFactory extends RequestMethodTypeFactory
{
    protected $factoryReturnArray = [
        'get' => GetRequestMethodResponseBuilder::class,
        'post' => PostRequestMethodResponseBuilder::class,
        'put' => PutRequestMethodResponseBuilder::class,
        'patch' => PatchRequestMethodResponseBuilder::class,
        'delete' => DeleteRequestMethodResponseBuilder::class,
    ];

    public function getFactoryItem($requestMethod) : RequestMethodResponseBuilder
    {
        return parent::getFactoryItem($requestMethod);
    }
}