<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;

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
            !$this->clauseBuilderClass && 
            (
                str_contains($this->parameterType, 'varchar') || 
                str_contains($this->parameterType, 'char') || 
                $this->parameterType == 'blob' || 
                $this->parameterType == 'text'
            )
        ) {
            $this->clauseBuilderClass = new StringParameterValidator();
        }
    }

    protected function checkForDateValidator()
    {
        if (
            !$this->clauseBuilderClass && 
            (
                $this->parameterType == 'date' || 
                $this->parameterType == 'timestamp' || 
                $this->parameterType == 'datetime' || 
                str_contains($this->parameterType, 'date')
            )
        ) {
            $this->clauseBuilderClass = new DateParameterValidator();
        }
    }

    protected function checkForIntValidator()
    {
        if (
            !$this->clauseBuilderClass && 
            (
                $this->parameterType == 'integer' ||
                $this->parameterType == 'int' ||
                $this->parameterType == 'smallint' ||
                $this->parameterType == 'tinyint' ||
                $this->parameterType == 'mediumint' ||
                $this->parameterType == 'bigint'
            )
        ) {
            $this->clauseBuilderClass = new IntParameterValidator();
        }
    }

    protected function checkForFloatValidator()
    {
        if (
            !$this->clauseBuilderClass && 
            (
                $this->parameterType == 'decimal' ||
                $this->parameterType == 'numeric' ||
                $this->parameterType == 'float' ||
                $this->parameterType == 'double'
            )
        ) {
            $this->clauseBuilderClass = new FloatParameterValidator();
        }
    }

    protected function checkForIdValidator()
    {
        if (
            !$this->clauseBuilderClass && 
            (
                $this->parameterType == 'date' || 
                $this->parameterType == 'timestamp' || 
                $this->parameterType == 'datetime' || 
                str_contains($this->parameterType, 'date')
            )
        ) {
            $this->clauseBuilderClass = new IdParameterValidator();
        }
    }

    protected function checkForOrderByValidator()
    {
        if (!$this->clauseBuilderClass && $this->parameterType == 'orderby') {
            $this->clauseBuilderClass = new OrderByParameterValidator();
        }
    }

    protected function checkForSelectValidator()
    {
        if (!$this->clauseBuilderClass && $this->parameterType == 'select') {
            $this->clauseBuilderClass = new SelectParameterValidator();
        }
    }

    protected function checkForIncludesValidator()
    {
        if (!$this->clauseBuilderClass && $this->parameterType == 'includes') {
            $this->clauseBuilderClass = new IncludesParameterValidator();
        }
    }

    protected function checkForMethodCallsValidator()
    {
        if (!$this->clauseBuilderClass && $this->parameterType == 'methodcalls') {
            $this->clauseBuilderClass = new MethodCallsParameterValidator();
        }
    }

}