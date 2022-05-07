<?php

namespace App\CoreIntegrationApi\ClauseBuilderFactory;

use App\CoreIntegrationApi\CIL\CILDataTypeDeterminerFactory;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\ClauseBuilder;

class ClauseBuilderFactory extends CILDataTypeDeterminerFactory
{
    public function getFactoryItem($dataType) : ClauseBuilder
    {
        $this->factoryReturnArray = [
            'string' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\StringWhereClauseBuilder',
            'json' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\JsonWhereClauseBuilder',
            'date' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\DateWhereClauseBuilder',
            'int' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\IntWhereClauseBuilder',
            'float' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\FloatWhereClauseBuilder',
            'orderby' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\OrderByClauseBuilder',
            'select' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\SelectClauseBuilder',
            'includes' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\IncludesClauseBuilder',
            'methodcalls' => 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\MethodCallsClauseBuilder',
        ];

        return parent::getFactoryItem($dataType);
    }
}