<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\RequestDataPrepper;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Support\Facades\DB;

abstract class RequestValidator 
{

    private $requestDataPrepper;
    private $validatorDataCollector;
    private $acceptedClasses;
    private $parameterValidatorFactory;
    private $class;
    private $endpoint;
    private $endpointId;
    private $parameters;
    private $defaultAcceptableParameters = [
        'orderby' => 'orderby', 
        'perpage' => 'perpage', 
        'column' => 'select', 
        'page' => 'page',
        'methodcalls' => 'methodcalls',
        'includes' => 'includes',
    ];
    private $acceptableParameters;
    private $validatedMetaData;
    
    function __construct(RequestDataPrepper $requestDataPrepper, ParameterValidatorFactory $parameterValidatorFactory, ValidatorDataCollector $validatorDataCollector) 
    {
        $this->requestDataPrepper = $requestDataPrepper;
        $this->acceptedClasses = config('coreintegration.acceptedclasses') ?? [];
        $this->parameterValidatorFactory = $parameterValidatorFactory;
        $this->validatorDataCollector = $validatorDataCollector;
    }   

    public function validate()
    {
        $this->requestDataPrepper->prep();

        dd($this->requestDataPrepper->getPreppedData());

        $this->validateRequest($this->requestDataPrepper->getPreppedData());

        return $this->validatedMetaData;
    }

    protected function validateRequest($prepRequestData)
    {
        $this->setUpPreppedRequest($prepRequestData);
        
        $this->validateEndPoint();
        $this->getAcceptableParameters();
        $this->validateParameters();

        $this->setValidatedMetaData();
    }

    protected function setUpPreppedRequest($prepRequestData)
    {
        $this->endpoint = $prepRequestData['endpoint'] ?? '';
        $this->endpointId = $prepRequestData['endpointId']  ?? [];
        $this->parameters = $prepRequestData['parameters'] ?? [];
    }

    protected function validateEndPoint()
    {
        if (array_key_exists($this->endpoint, $this->acceptedClasses) ) {
            $this->class = $this->acceptedClasses[$this->endpoint]; 
            $this->checkForIdParameterIfThereSetItAppropriately();
        } elseif ($this->endpoint != 'index') {
            // ! start here ****************************************************************
            // set endpoint error
        } 
    }

    protected function checkForIdParameterIfThereSetItAppropriately()
    {
        if ($this->endpointId) {
            $class = new $this->class();
            $primaryKeyName = $class->getKeyName() ? $class->getKeyName() : 'id';
            $this->parameters['otherParameters'][$primaryKeyName] = $this->endpointId;
        }
    }

    // TODO: Returns database data type with validated information
    // TODO: when finding acceptable parameters, Besides the default, apply parameter type to array of parameter information

    protected function getAcceptableParameters()
    {
        $this->getModelDBInfo();
        $tempClass = new $this->class();
        $classTableName = $tempClass->gettable();
        $columnData = $this->arrayOfObjectsToArrayOfArrays(DB::select("SHOW COLUMNS FROM {$classTableName}"));
        $this->setAcceptableParameters($columnData);

        // set $this->acceptableParameters
        return $columnData;
    }

    protected function arrayOfObjectsToArrayOfArrays(array $arrayOfObjects)
    {
        foreach ($arrayOfObjects as $object) {
            $arrayOfArrays[] = (array) $object;
        }

        return $arrayOfArrays;
    }

    protected function setAcceptableParameters(array $classDBData)
    {
        foreach ($classDBData as $columnArray) {
            foreach ($columnArray as $column_data_name => $value) {
                $column_data_name = strtolower($column_data_name);
                $value = $value === Null ? $value : strtolower($value);

                $this->acceptableParameters[$columnArray['Field']][$column_data_name] = $value; 
            }
        }
    }

    private function setClass()
    {
        if ($this->request->endpoint && isset($this->acceptedClasses[$this->request->endpoint])) {
            $this->preppedData['class'] = $this->acceptedClasses[$this->request->endpoint];
        } else {
            $this->preppedData['class'] = NULL; 
        }
    }

    // get validation but what about the others put patch post
    protected function validateParameters()
    {
        $allAcceptableParameters = array_merge($this->acceptableParameters, $this->defaultAcceptableParameters);

        foreach ($this->parameters as $key => $value) {
            if (array_key_exists($key, $allAcceptableParameters)) {
                $parameterValidator = $this->parameterValidatorFactory->getFactoryItem($allAcceptableParameters[$key]['type'] ?? $allAcceptableParameters[$key]);
                $this->validatorDataCollector = $parameterValidator->validate($this->validatorDataCollector, [$key => $value]);
            } else {
                $this->validatorDataCollector->setRejectedParameter([
                    $key => [
                        $key => $value,
                        'parameterError' => 'This is an invalid parameter for this endpoint.'
                    ]
                ]);
            }
        }
        // code...
        // use $this->acceptableParameters
        // use $this->defaultAcceptableParameters
        // Run them through a data preper or Parameter validator
        // All parameter validation needs to be done here

        // Use parameter validator factory
    }

    protected function setValidatedMetaData()
    {
        $validatedRequestMetaData['rejectedParameters'] = $this->validatorDataCollector->getRejectedParameters();
        $validatedRequestMetaData['acceptedParameters'] = $this->validatorDataCollector->getAcceptedParameters();
        // $validatedRequestMetaData['queryArguments'] = $this->validatorDataCollector->getQueryArguments(); // don't know if we need to send this one
        $this->validatedMetaData = $validatedRequestMetaData;
    }

    // I don't know if we need this
    public function getValidatedQueryData() 
    {
        return $this->validatedMetaData;
    }
}