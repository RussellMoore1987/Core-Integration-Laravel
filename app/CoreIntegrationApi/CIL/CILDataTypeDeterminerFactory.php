<?php

namespace App\CoreIntegrationApi\CIL;

abstract class CILDataTypeDeterminerFactory
{
    private $factoryItem;
    private $parameterType;
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

    public function getFactoryItem($parameterType)
    {
        $this->parameterType = strtolower($parameterType);
        
        $this->checkForStringValidator();
        $this->checkForDateValidator();
        $this->checkForIntValidator();
        $this->checkForFloatValidator();
        $this->checkForIdValidator();
        $this->checkForOrderByValidator();
        $this->checkForSelectValidator();
        $this->checkForIncludesValidator();
        $this->checkForMethodCallsValidator();

        return $this->factoryItem;
    }  

    protected function checkForStringValidator()
    {
        if (
            !$this->factoryItem && 
            (
                str_contains($this->parameterType, 'varchar') || 
                str_contains($this->parameterType, 'char') || 
                $this->parameterType == 'blob' || 
                $this->parameterType == 'text'
            )
        ) {
            $this->factoryItem = $this->factoryReturnArray['string'];
        }
    }

    protected function checkForDateValidator()
    {
        if (
            !$this->factoryItem && 
            (
                $this->parameterType == 'date' || 
                $this->parameterType == 'timestamp' || 
                $this->parameterType == 'datetime' || 
                str_contains($this->parameterType, 'date')
            )
        ) {
            $this->factoryItem = $this->factoryReturnArray['date'];
        }
    }

    protected function checkForIntValidator()
    {
        if (
            !$this->factoryItem && 
            (
                $this->parameterType == 'integer' ||
                $this->parameterType == 'int' ||
                $this->parameterType == 'smallint' ||
                $this->parameterType == 'tinyint' ||
                $this->parameterType == 'mediumint' ||
                $this->parameterType == 'bigint'
            )
        ) {
            $this->factoryItem = $this->factoryReturnArray['int'];
        }
    }

    protected function checkForFloatValidator()
    {
        if (
            !$this->factoryItem && 
            (
                $this->parameterType == 'decimal' ||
                $this->parameterType == 'numeric' ||
                $this->parameterType == 'float' ||
                $this->parameterType == 'double'
            )
        ) {
            $this->factoryItem = $this->factoryReturnArray['float'];
        }
    }

    protected function checkForIdValidator()
    {
        if (!$this->factoryItem && $this->parameterType == 'id') 
        {
            $this->factoryItem = $this->factoryReturnArray['id'];
        }
    }

    protected function checkForOrderByValidator()
    {
        if (!$this->factoryItem && $this->parameterType == 'orderby') 
        {
            $this->factoryItem = $this->factoryReturnArray['orderby'];
        }
    }

    protected function checkForSelectValidator()
    {
        if (!$this->factoryItem && $this->parameterType == 'select') 
        {
            $this->factoryItem = $this->factoryReturnArray['select'];
        }
    }

    protected function checkForIncludesValidator()
    {
        if (!$this->factoryItem && $this->parameterType == 'includes') 
        {
            $this->factoryItem = $this->factoryReturnArray['includes'];
        }
    }

    protected function checkForMethodCallsValidator()
    {
        if (!$this->factoryItem && $this->parameterType == 'methodcalls') 
        {
            $this->factoryItem = $this->factoryReturnArray['methodcalls'];
        }
    }

}