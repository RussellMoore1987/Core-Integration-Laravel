<?php

namespace App\CoreIntegrationApi\CIL;

use Illuminate\Support\Facades\App;

// use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;

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
                str_contains($this->dataType, 'varchar') || 
                str_contains($this->dataType, 'char') || 
                $this->dataType == 'blob' || 
                $this->dataType == 'text'
            )
        ) {
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['string']);
        }
    }

    protected function checkForDateValidator()
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
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['date']);
        }
    }

    protected function checkForIntValidator()
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
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['int']);
        }
    }

    protected function checkForFloatValidator()
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
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['float']);
        }
    }

    protected function checkForIdValidator()
    {
        if (!$this->factoryItem && $this->dataType == 'id') 
        {
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['id']);
        }
    }

    protected function checkForOrderByValidator()
    {
        if (!$this->factoryItem && $this->dataType == 'orderby') 
        {
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['orderby']);
        }
    }

    protected function checkForSelectValidator()
    {
        if (!$this->factoryItem && $this->dataType == 'select') 
        {
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['select']);
        }
    }

    protected function checkForIncludesValidator()
    {
        if (!$this->factoryItem && $this->dataType == 'includes') 
        {
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['includes']);
        }
    }

    protected function checkForMethodCallsValidator()
    {
        if (!$this->factoryItem && $this->dataType == 'methodcalls') 
        {
            $this->factoryItem = $this->returnStringOrClass($this->factoryReturnArray['methodcalls']);
        }
    }

    protected function returnStringOrClass($dataTypeVale)
    {
        return str_contains($dataTypeVale, '\\') ? App::make($dataTypeVale) : $dataTypeVale;
    }

}