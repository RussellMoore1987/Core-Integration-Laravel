<?php

namespace App\CoreIntegrationApi\ParameterValidatorFactory;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidators\StringParameterValidator;

class ParameterValidatorFactory
{
    private $parameterValidatorClass;
    private $parameterType;

    public function getParameterValidator($parameterType)
    {
        $this->parameterType = $parameterType;
        
        // ! Start here ************************************************************************
        $this->checkForStringValidator();

        return $this->parameterValidatorClass;


        // some code to work with
        if ($parameterType == 'select') {
            // return new SelectClauseBuilder();
        } elseif ($parameterType == 'orderBy') {
            // return new OrderByClauseBuilder();
        } elseif (
            $parameterType == 'date' || 
            $parameterType == 'timestamp' || 
            $parameterType == 'datetime' || 
            str_contains($parameterType, 'date')
        ) {
            // return new DateWhereClauseBuilder();
        } elseif (
            str_contains($parameterType, 'varchar') || 
            str_contains($parameterType, 'char') || 
            $parameterType == 'blob' || 
            $parameterType == 'text'
        ) {
            // return new StringWhereClauseBuilder();
        } elseif (
            $parameterType == 'integer' ||
            $parameterType == 'int' ||
            $parameterType == 'smallint' ||
            $parameterType == 'tinyint' ||
            $parameterType == 'mediumint' ||
            $parameterType == 'bigint'
        ) {
            return 'int';
        } elseif (
            $parameterType == 'decimal' ||
            $parameterType == 'numeric' ||
            $parameterType == 'float' ||
            $parameterType == 'double'
        ) {
            return 'float';
        } else { // Maybe throw an exception here ???
            foreach ($this->currentParameter as $parameter => $value) {
                unset($this->paramsAccepted[$parameter]);
                $this->paramsRejected[$parameter] = "Column type for \"{$parameter}\" is not supported in the query processor, contact the API administer for help!";
            }
            return false;
        } 
        
    }  

    private function checkForStringValidator()
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
}