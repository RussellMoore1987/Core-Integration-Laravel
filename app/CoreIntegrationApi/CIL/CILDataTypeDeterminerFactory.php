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
        $this->factoryItem = null; // rests if used more then once

        $this->isString();
        $this->isJson();
        $this->isDate();
        $this->isInt();
        $this->isFloat();
        $this->isOrderBy();
        $this->isSelect();
        $this->isIncludes();
        $this->isMethodCalls();

        return $this->factoryItem;
    }

    protected function isString() : void
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
            $this->setFactoryItem($this->factoryReturnArray['string']);
        }
    }

    protected function isJson() : void
    {
        if (!$this->factoryItem && str_contains($this->dataType, 'json')) {
            $this->setFactoryItem($this->factoryReturnArray['json']);
        }
    }

    protected function isDate() : void
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
            $this->setFactoryItem($this->factoryReturnArray['date']);
        }
    }

    protected function isInt() : void
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
            $this->setFactoryItem($this->factoryReturnArray['int']);
        }
    }

    protected function isFloat() : void
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
            $this->setFactoryItem($this->factoryReturnArray['float']);
        }
    }

    protected function isOrderBy() : void
    {
        if (!$this->factoryItem && $this->dataType == 'orderby')
        {
            $this->setFactoryItem($this->factoryReturnArray['orderby']);
        }
    }

    protected function isSelect() : void
    {
        if (!$this->factoryItem && $this->dataType == 'select')
        {
            $this->setFactoryItem($this->factoryReturnArray['select']);
        }
    }

    protected function isIncludes() : void
    {
        if (!$this->factoryItem && $this->dataType == 'includes')
        {
            $this->setFactoryItem($this->factoryReturnArray['includes']);
        }
    }

    protected function isMethodCalls() : void
    {
        if (!$this->factoryItem && $this->dataType == 'methodcalls')
        {
            $this->setFactoryItem($this->factoryReturnArray['methodcalls']);
        }
    }

    protected function setFactoryItem($dataTypeValue) : void
    {
        $this->factoryItem = App::make($dataTypeValue);
    }
}