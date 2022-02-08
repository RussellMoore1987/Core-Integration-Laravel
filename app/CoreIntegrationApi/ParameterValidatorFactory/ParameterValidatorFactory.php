<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\DateParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IntParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\FloatParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IdParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\OrderByParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\SelectParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\IncludesParameterValidator;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\MethodCallsParameterValidator;

class ParameterValidatorFactory
{
    private $parameterValidatorClass;
    private $parameterType;

    public function getParameterValidator($parameterType)
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

        return $this->parameterValidatorClass;
    }  

    protected function checkForStringValidator()
    {
        if (
            !$this->parameterValidatorClass && 
            (
                str_contains($this->parameterType, 'varchar') || 
                str_contains($this->parameterType, 'char') || 
                $this->parameterType == 'blob' || 
                $this->parameterType == 'text'
            )
        ) {
            $this->parameterValidatorClass = new StringParameterValidator();
        }
    }

    protected function checkForDateValidator()
    {
        if (
            !$this->parameterValidatorClass && 
            (
                $this->parameterType == 'date' || 
                $this->parameterType == 'timestamp' || 
                $this->parameterType == 'datetime' || 
                str_contains($this->parameterType, 'date')
            )
        ) {
            $this->parameterValidatorClass = new DateParameterValidator();
        }
    }

    protected function checkForIntValidator()
    {
        if (
            !$this->parameterValidatorClass && 
            (
                $this->parameterType == 'integer' ||
                $this->parameterType == 'int' ||
                $this->parameterType == 'smallint' ||
                $this->parameterType == 'tinyint' ||
                $this->parameterType == 'mediumint' ||
                $this->parameterType == 'bigint'
            )
        ) {
            $this->parameterValidatorClass = new IntParameterValidator();
        }
    }

    protected function checkForFloatValidator()
    {
        if (
            !$this->parameterValidatorClass && 
            (
                $this->parameterType == 'decimal' ||
                $this->parameterType == 'numeric' ||
                $this->parameterType == 'float' ||
                $this->parameterType == 'double'
            )
        ) {
            $this->parameterValidatorClass = new FloatParameterValidator();
        }
    }

    protected function checkForIdValidator()
    {
        if (
            !$this->parameterValidatorClass && 
            (
                $this->parameterType == 'date' || 
                $this->parameterType == 'timestamp' || 
                $this->parameterType == 'datetime' || 
                str_contains($this->parameterType, 'date')
            )
        ) {
            $this->parameterValidatorClass = new IdParameterValidator();
        }
    }

    protected function checkForOrderByValidator()
    {
        if (!$this->parameterValidatorClass && $this->parameterType == 'orderby') {
            $this->parameterValidatorClass = new OrderByParameterValidator();
        }
    }

    protected function checkForSelectValidator()
    {
        if (!$this->parameterValidatorClass && $this->parameterType == 'select') {
            $this->parameterValidatorClass = new SelectParameterValidator();
        }
    }

    protected function checkForIncludesValidator()
    {
        if (!$this->parameterValidatorClass && $this->parameterType == 'includes') {
            $this->parameterValidatorClass = new IncludesParameterValidator();
        }
    }

    protected function checkForMethodCallsValidator()
    {
        if (!$this->parameterValidatorClass && $this->parameterType == 'methodcalls') {
            $this->parameterValidatorClass = new MethodCallsParameterValidator();
        }
    }

}