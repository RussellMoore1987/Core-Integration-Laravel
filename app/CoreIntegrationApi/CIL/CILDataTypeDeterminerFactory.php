<?php

namespace App\CoreIntegrationApi\CIL;

use Illuminate\Support\Facades\App;

abstract class CILDataTypeDeterminerFactory
{
    protected $factoryItem;
    protected $factoryItemTrigger;
    protected $dataType;
    // Just placeholder strings, should be replaced by paths to the actual classes, see app\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory.php for example
    protected $factoryReturnArray = [
        'string' => 'string',
        'json' => 'json',
        'date' => 'date',
        'int' => 'int',
        'float' => 'float',
        'id' => 'id',
        'orderby' => 'orderby',
        'select' => 'select',
        'includes' => 'includes',
        'methodcalls' => 'methodcalls',
    ];

    public function getFactoryItem($dataType)
    {
        $this->dataType = strtolower($dataType);
        $this->factoryItem = null;

        $this->checkForString();
        $this->checkForJson();
        $this->checkForDate();
        $this->checkForInt();
        $this->checkForFloat();
        $this->checkForOrderBy();
        $this->checkForSelect();
        $this->checkForIncludes();
        $this->checkForMethodCalls();

        $this->factoryItemTrigger = null;

        return $this->factoryItem;
    }  

    protected function checkForString()
    {
        if (
            !$this->factoryItemTrigger && 
            (
                str_contains($this->dataType, 'varchar') || 
                str_contains($this->dataType, 'char') || 
                $this->dataType == 'blob' || 
                $this->dataType == 'text' ||
                $this->dataType == 'string'
            )
        ) {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['string']);
        }
    }

    protected function checkForJson()
    {
        if (!$this->factoryItemTrigger && str_contains($this->dataType, 'json')) {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['json']);
        }
    }

    protected function checkForDate()
    {
        if (
            !$this->factoryItemTrigger && 
            (
                $this->dataType == 'date' || 
                $this->dataType == 'timestamp' || 
                $this->dataType == 'datetime' || 
                str_contains($this->dataType, 'date')
            )
        ) {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['date']);
        }
    }

    protected function checkForInt()
    {
        if (
            !$this->factoryItemTrigger && 
            (
                $this->dataType == 'integer' ||
                $this->dataType == 'int' ||
                $this->dataType == 'smallint' ||
                $this->dataType == 'tinyint' ||
                $this->dataType == 'mediumint' ||
                $this->dataType == 'bigint' ||
                (str_contains($this->dataType, 'int') && str_contains($this->dataType, 'unsigned'))
            )
        ) {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['int']);
        }
    }

    protected function checkForFloat()
    {
        if (
            !$this->factoryItemTrigger && 
            (
                $this->dataType == 'decimal' || str_contains($this->dataType, 'decimal') ||
                $this->dataType == 'numeric' || str_contains($this->dataType, 'numeric') ||
                $this->dataType == 'float' || str_contains($this->dataType, 'float') ||
                $this->dataType == 'double' || str_contains($this->dataType, 'double')
            )
        ) {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['float']);
        }
    }

    protected function checkForOrderBy()
    {
        if (!$this->factoryItemTrigger && $this->dataType == 'orderby') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['orderby']);
        }
    }

    protected function checkForSelect()
    {
        if (!$this->factoryItemTrigger && $this->dataType == 'select') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['select']);
        }
    }

    protected function checkForIncludes()
    {
        if (!$this->factoryItemTrigger && $this->dataType == 'includes') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['includes']);
        }
    }

    protected function checkForMethodCalls()
    {
        if (!$this->factoryItemTrigger && $this->dataType == 'methodcalls') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['methodcalls']);
        }
    }

    protected function returnValue($dataTypeValue)
    {
        return App::make($dataTypeValue);
    }
}