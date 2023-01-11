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
                str_contains($this->dataType, 'blob') ||
                str_contains($this->dataType, 'text') ||
                $this->dataType == 'enum' ||
                $this->dataType == 'set'
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
                $this->dataType == 'year' ||
                $this->dataType == 'timestamp' ||
                str_contains($this->dataType, 'date')
            )
        ) {
            $this->setFactoryItem($this->factoryItemArray['date']);
        }
    }

    protected function isInt() : void
    {
        if ($this->factoryItemIsNotSet() && (str_contains($this->dataType, 'int') || $this->dataType == 'bit')) {
            $this->setFactoryItem($this->factoryItemArray['int']);
        }
    }

    protected function isFloat() : void
    {
        if (
            $this->factoryItemIsNotSet() &&
            (
                str_contains($this->dataType, 'decimal') ||
                str_contains($this->dataType, 'numeric') ||
                str_contains($this->dataType, 'float') ||
                str_contains($this->dataType, 'double')
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

    protected function setFactoryItem($apiParameterClassPath) : void
    {
        $this->factoryItem = App::make($apiParameterClassPath);
    }
}
