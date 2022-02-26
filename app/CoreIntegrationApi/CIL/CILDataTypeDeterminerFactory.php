<?php

namespace App\CoreIntegrationApi\CIL;

abstract class CILDataTypeDeterminerFactory
{
    private $factoryItem;
    private $dataType;
    protected $factoryReturnArray = [
        'string' => 'string',
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
        
        $this->checkForString();
        $this->checkForDate();
        $this->checkForInt();
        $this->checkForFloat();
        $this->checkForOrderBy();
        $this->checkForSelect();
        $this->checkForIncludes();
        $this->checkForMethodCalls();

        return $this->factoryItem;
    }  

    protected function checkForString()
    {
        if (
            !$this->factoryItem && 
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

    protected function checkForDate()
    {
        if (
            !$this->factoryItem && 
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
            !$this->factoryItem && 
            (
                $this->dataType == 'integer' ||
                $this->dataType == 'int' ||
                $this->dataType == 'smallint' ||
                $this->dataType == 'tinyint' ||
                $this->dataType == 'mediumint' ||
                $this->dataType == 'bigint'
            )
        ) {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['int']);
        }
    }

    protected function checkForFloat()
    {
        if (
            !$this->factoryItem && 
            (
                $this->dataType == 'decimal' ||
                $this->dataType == 'numeric' ||
                $this->dataType == 'float' ||
                $this->dataType == 'double'
            )
        ) {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['float']);
        }
    }

    protected function checkForOrderBy()
    {
        if (!$this->factoryItem && $this->dataType == 'orderby') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['orderby']);
        }
    }

    protected function checkForSelect()
    {
        if (!$this->factoryItem && $this->dataType == 'select') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['select']);
        }
    }

    protected function checkForIncludes()
    {
        if (!$this->factoryItem && $this->dataType == 'includes') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['includes']);
        }
    }

    protected function checkForMethodCalls()
    {
        if (!$this->factoryItem && $this->dataType == 'methodcalls') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['methodcalls']);
        }
    }

    protected function returnValue($dataTypeValue)
    {
        return $dataTypeValue;
    }
}