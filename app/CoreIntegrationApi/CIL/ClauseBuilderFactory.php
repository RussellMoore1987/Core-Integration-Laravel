<?php

namespace App\CoreIntegrationApi\CIL;

use App\CoreIntegrationApi\CIL\ClauseBuilder\DateWhereClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\OrderByClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\SelectClauseBuilder;
use App\CoreIntegrationApi\CIL\ClauseBuilder\StringWhereClauseBuilder;

class ClauseBuilderFactory
{
    private $clauseBuilderClass;
    private $parameterType;

    public function getClauseBuilder($parameterType)
    {
        $this->parameterType = $parameterType;

        // find and return type
        // types 
            // select
            // column - Already listed below, there are probably more but this is a fairly sufficient list for now. 
                // ex: 
                    // $parameterType == 'date'
                    // $parameterType == 'varchar'
                    // $parameterType == 'datetime'
                    // $parameterType == 'integer'
                // date
                // string
                // int
                // float
            // include
            // orderBy
            // methodCall
            // id
        $this->checkForSelect(); // *** use this method. This will help break up the big if statement.
        // $this->checkForOrderBy();
        // $this->checkForDate();
        // etc.

        return $this->clauseBuilderClass;


        // some code to work with
        if ($parameterType == 'select') {
            return new SelectClauseBuilder();
        } elseif ($parameterType == 'orderBy') {
            return new OrderByClauseBuilder();
        } elseif (
            $parameterType == 'date' || 
            $parameterType == 'timestamp' || 
            $parameterType == 'datetime' || 
            str_contains($parameterType, 'date')
        ) {
            return new DateWhereClauseBuilder();
        } elseif (
            str_contains($parameterType, 'varchar') || 
            str_contains($parameterType, 'char') || 
            $parameterType == 'blob' || 
            $parameterType == 'text'
        ) {
            return new StringWhereClauseBuilder();
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

    private function checkForSelect()
    {
        if (!$this->clauseBuilderClass && $this->parameterType == 'select') {
            $this->clauseBuilderClass = new SelectClauseBuilder();
        }
    }
}