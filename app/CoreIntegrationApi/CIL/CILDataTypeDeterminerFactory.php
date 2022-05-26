<?php

namespace App\CoreIntegrationApi\CIL;

use Illuminate\Support\Facades\App;

abstract class CILDataTypeDeterminerFactory
{
    protected $factoryItem;
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

    public function getFactoryItem($dataType) : object
    {
        $this->dataType = strtolower($dataType);
        $this->factoryItem = null;

        $this->checkForStringIfThereSetFactoryItem();
        $this->checkForJsonIfThereSetFactoryItem();
        $this->checkForDateIfThereSetFactoryItem();
        $this->checkForIntIfThereSetFactoryItem();
        $this->checkForFloatIfThereSetFactoryItem();
        $this->checkForOrderByIfThereSetFactoryItem();
        $this->checkForSelectIfThereSetFactoryItem();
        $this->checkForIncludesIfThereSetFactoryItem();
        $this->checkForMethodCallsIfThereSetFactoryItem();

        return $this->factoryItem;
    }  

    protected function checkForStringIfThereSetFactoryItem()
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

    protected function checkForJsonIfThereSetFactoryItem()
    {
        if (!$this->factoryItem && str_contains($this->dataType, 'json')) {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['json']);
        }
    }

    protected function checkForDateIfThereSetFactoryItem()
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

    protected function checkForIntIfThereSetFactoryItem()
    {
        if (
            !$this->factoryItem && 
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

    protected function checkForFloatIfThereSetFactoryItem()
    {
        if (
            !$this->factoryItem && 
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

    protected function checkForOrderByIfThereSetFactoryItem()
    {
        if (!$this->factoryItem && $this->dataType == 'orderby') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['orderby']);
        }
    }

    protected function checkForSelectIfThereSetFactoryItem()
    {
        if (!$this->factoryItem && $this->dataType == 'select') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['select']);
        }
    }

    protected function checkForIncludesIfThereSetFactoryItem()
    {
        if (!$this->factoryItem && $this->dataType == 'includes') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['includes']);
        }
    }

    protected function checkForMethodCallsIfThereSetFactoryItem()
    {
        if (!$this->factoryItem && $this->dataType == 'methodcalls') 
        {
            $this->factoryItem = $this->returnValue($this->factoryReturnArray['methodcalls']);
        }
    }

    protected function returnValue($dataTypeValue)
    {
        return App::make($dataTypeValue);
    }
}