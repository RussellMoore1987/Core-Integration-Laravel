<?php

namespace App\CoreIntegrationApi\CIL;

use App\CoreIntegrationApi\CIL\CILDataTypeDeterminerFactory;

use Illuminate\Support\Facades\App;

class ClauseBuilderFactory extends CILDataTypeDeterminerFactory
{
    public function getFactoryItem($dataType)
    {
        $this->factoryReturnArray = [
            'string' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\StringWhereClauseBuilder',
            'date' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\DateWhereClauseBuilder',
            'int' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\IntWhereClauseBuilder',
            'float' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\FloatWhereClauseBuilder',
            'id' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\IdClauseBuilder',
            'orderby' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\OrderByClauseBuilder',
            'select' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\SelectClauseBuilder',
            'includes' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\IncludesClauseBuilder',
            'methodcalls' => 'App\CoreIntegrationApi\CIL\ClauseBuilder\MethodCallsClauseBuilder',
        ];

        return parent::getFactoryItem($dataType);
    }
}