<?php

namespace App\CoreIntegrationApi\ClauseBuilderFactory;

use App\CoreIntegrationApi\CIL\CILDataTypeDeterminerFactory;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders\ClauseBuilder;

class ClauseBuilderFactory extends CILDataTypeDeterminerFactory
{
    public function getFactoryItem($dataType) : ClauseBuilder
    {
        $classPath = 'App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilders';

        $this->factoryReturnArray = [
            'string' => "{$classPath}\StringWhereClauseBuilder",
            'json' => "{$classPath}\JsonWhereClauseBuilder",
            'date' => "{$classPath}\DateWhereClauseBuilder",
            'int' => "{$classPath}\IntWhereClauseBuilder",
            'float' => "{$classPath}\FloatWhereClauseBuilder",
            'orderby' => "{$classPath}\OrderByClauseBuilder",
            'select' => "{$classPath}\SelectClauseBuilder",
            'includes' => "{$classPath}\IncludesClauseBuilder",
            'methodcalls' => "{$classPath}\MethodCallsClauseBuilder",
        ];

        return parent::getFactoryItem($dataType);
    }
}