<?php

namespace App\CoreIntegrationApi\ClauseBuilderFactory;

use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\ClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\StringWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\JsonWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\DateWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\IntWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\FloatWhereClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\OrderByClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\SelectClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\IncludesClauseBuilder;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\MethodCallsClauseBuilder;

class ClauseBuilderFactory extends DataTypeDeterminerFactory
{
    protected $factoryItemArray = [
        'string' => StringWhereClauseBuilder::class,
        'json' => JsonWhereClauseBuilder::class,
        'date' => DateWhereClauseBuilder::class,
        'int' => IntWhereClauseBuilder::class,
        'float' => FloatWhereClauseBuilder::class,
        'orderby' => OrderByClauseBuilder::class,
        'select' => SelectClauseBuilder::class,
        'includes' => IncludesClauseBuilder::class,
        'methodcalls' => MethodCallsClauseBuilder::class,
    ];

    public function getFactoryItem($dataType): ClauseBuilder
    {
        return parent::getFactoryItem($dataType);
    }
}