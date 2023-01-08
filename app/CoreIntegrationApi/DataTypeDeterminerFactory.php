<?php

namespace App\CoreIntegrationApi;

use Illuminate\Support\Facades\App;

abstract class DataTypeDeterminerFactory
{
    protected $factoryItem;
    protected $dataType;
    // Just placeholder strings, should be replaced by paths to the actual classes, see app\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory.php for example
    protected $factoryItemArray = [
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

    public function getFactoryItem(string $dataType) : object
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
            $this->factoryItemIsNotSet() &&
            (
                str_contains($this->dataType, 'varchar') ||
                str_contains($this->dataType, 'char') ||
                $this->dataType == 'blob' ||
                $this->dataType == 'text' ||
                $this->dataType == 'string'
            )
        ) {
            $this->setFactoryItem($this->factoryItemArray['string']);
        }
    }

    protected function isJson() : void
    {
        if ($this->factoryItemIsNotSet() && str_contains($this->dataType, 'json')) {
            $this->setFactoryItem($this->factoryItemArray['json']);
        }
    }

    protected function isDate() : void
    {
        if (
            $this->factoryItemIsNotSet() &&
            (
                $this->dataType == 'date' ||
                $this->dataType == 'timestamp' ||
                $this->dataType == 'datetime' ||
                str_contains($this->dataType, 'date')
            )
        ) {
            $this->setFactoryItem($this->factoryItemArray['date']);
        }
    }

    protected function isInt() : void
    {
        if (
            $this->factoryItemIsNotSet() &&
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
            $this->setFactoryItem($this->factoryItemArray['int']);
        }
    }

    protected function isFloat() : void
    {
        if (
            $this->factoryItemIsNotSet() &&
            (
                $this->dataType == 'decimal' || str_contains($this->dataType, 'decimal') ||
                $this->dataType == 'numeric' || str_contains($this->dataType, 'numeric') ||
                $this->dataType == 'float' || str_contains($this->dataType, 'float') ||
                $this->dataType == 'double' || str_contains($this->dataType, 'double')
            )
        ) {
            $this->setFactoryItem($this->factoryItemArray['float']);
        }
    }

    protected function isOrderBy() : void
    {
        if ($this->factoryItemIsNotSet() && $this->dataType == 'orderby') {
            $this->setFactoryItem($this->factoryItemArray['orderby']);
        }
    }

    protected function isSelect() : void
    {
        if ($this->factoryItemIsNotSet() && $this->dataType == 'select') {
            $this->setFactoryItem($this->factoryItemArray['select']);
        }
    }

    protected function isIncludes() : void
    {
        if ($this->factoryItemIsNotSet() && $this->dataType == 'includes') {
            $this->setFactoryItem($this->factoryItemArray['includes']);
        }
    }

    protected function isMethodCalls() : void
    {
        if ($this->factoryItemIsNotSet() && $this->dataType == 'methodcalls') {
            $this->setFactoryItem($this->factoryItemArray['methodcalls']);
        }
    }

    protected function factoryItemIsNotSet() : bool
    {
        return !$this->factoryItem;
    }

    protected function setFactoryItem($dataTypeValue) : void
    {
        $this->factoryItem = App::make($dataTypeValue);
    }
}
