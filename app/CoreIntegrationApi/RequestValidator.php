<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\DataTypeDeterminerFactory;
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
    private $endpointError;
    private $request;
    private $parameters;
    private $defaultAcceptableParameters = ['per_page', 'perpage', 'page', 'column_data', 'columndata'];
    private $getMethodParameterValidatorDefaults = [
        'column' => 'select', 
        'select' => 'select', 
        'orderby' => 'orderby', 
        'order_by' => 'orderby', 
        'methodcalls' => 'methodcalls',
        'method_calls' => 'methodcalls',
        'includes' => 'includes',
    ];
    private $acceptableParameters = [];
    private $validatedMetaData;
    
    function __construct(RequestDataPrepper $requestDataPrepper, DataTypeDeterminerFactory $dataTypeDeterminerFactory, ParameterValidatorFactory $parameterValidatorFactory, ValidatorDataCollector $validatorDataCollector) 
    {
        $this->requestDataPrepper = $requestDataPrepper;
        $this->acceptedClasses = config('coreintegration.acceptedclasses') ?? [];
        $this->dataTypeDeterminerFactory = $dataTypeDeterminerFactory;
        $this->parameterValidatorFactory = $parameterValidatorFactory;
        $this->validatorDataCollector = $validatorDataCollector;
        $this->request = request();
    }   

    public function validate()
    {
        $this->requestDataPrepper->prep();

        $this->validateRequest($this->requestDataPrepper->getPreppedData());

        return $this->validatedMetaData;
    }

    protected function validateRequest($prepRequestData)
    {
        $this->setUpPreppedRequest($prepRequestData);
        
        $this->validateEndPoint();
        $this->getAcceptableParameters();
        $this->validateParameters();
        
        $this->setExtraData();
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
            $this->setEndpoint();
        } elseif ($this->endpoint != 'index') {
            $this->setEndpointError();
        } 
    }

    protected function setEndpoint()
    {
        $this->class = $this->acceptedClasses[$this->endpoint]; 
        $this->checkForIdParameterIfThereSetItAppropriately();
        $this->validatorDataCollector->setAcceptedParameter([
            "endpoint" => [
                'messsage' => "\"$this->endpoint\" is a valid endpoint for this API. You can also review available endpoints at " . $this->getIndexUrl(), 
                'error' => false
            ]
        ]);
    }

    protected function checkForIdParameterIfThereSetItAppropriately()
    {
        if ($this->endpointId) {
            $class = new $this->class();
            $primaryKeyName = $class->getKeyName() ? $class->getKeyName() : 'id';
            $this->parameters['otherParameters'][$primaryKeyName] = $this->endpointId;
        }
        $this->validatorDataCollector->setEndpointData(
            [
                'endpoint' => $this->endpoint, 
                'endpointId' => $this->endpointId, 
                'endpointIdConvertedTo' => [$primaryKeyName => $this->endpointId], 
                'indexUrl' => $this->getIndexUrl(),
                'url' => $this->request->url(),
                'HTTP method' => $this->request->getMethod(),
            ]
        );
    }

    protected function getIndexUrl()
    {
        return substr($this->request->url(), 0, strpos($this->request->url(), 'api/v1/') + 7);
    }

    protected function setEndpointError()
    {
        $this->endpointError = true;
        $this->validatorDataCollector->setRejectedParameter([
            'endpoint' => [
                'messsage' => "\"$this->endpoint\" is not a valid endpoint for this API. Pleaase review available endpoints at " . $this->getIndexUrl(), 
                'error' => true
            ]
        ]);
        if ($this->endpointId) {
            $this->validatorDataCollector->setRejectedParameter([
                'endpointId' => [
                    'messsage' => "\"$this->endpoint\" is not a valid endpoint for this API, therefore the endpoint ID is invalid as well. Pleaase review available endpoints at " . $this->getIndexUrl(), 
                    'value' => $this->endpointId
                ]
            ]);
        }
        $this->validatorDataCollector->setEndpointData(
            [
                'endpoint' => $this->endpoint, 
                'endpointId' => $this->endpointId, 
                'indexUrl' => $this->getIndexUrl(),
                'url' => $this->request->url(),
                'HTTP method' => $this->request->getMethod(),
            ]
        );
    }

    protected function getAcceptableParameters()
    {
        if (!$this->endpointError) {
            $tempClass = new $this->class();
            $classTableName = $tempClass->gettable();
            $columnData = $this->arrayOfObjectsToArrayOfArrays(DB::select("SHOW COLUMNS FROM {$classTableName}"));
            $this->setAcceptableParameters($columnData);
            $this->addApiDataTypeToAcceptableParameters();
        }
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

    protected function addApiDataTypeToAcceptableParameters()
    {
        foreach ($this->acceptableParameters as $key => $columnArray) {
            $this->acceptableParameters[$key]['ApiDataType'] = $this->dataTypeDeterminerFactory->getFactoryItem($columnArray['type']);   
        }
    }

    // TODO: get validation but what about the others put patch post
    protected function validateParameters()
    {
        foreach ($this->parameters['otherParameters'] as $key => $value) {
            $key = strtolower($key);
            $data = [$key => $value];
            if (array_key_exists($key, $this->acceptableParameters)) {
                $dataType = $this->acceptableParameters[$key]['type'];
                $this->getMethodParameterValidator($dataType, $data);
            } elseif (array_key_exists($key, $this->getMethodParameterValidatorDefaults)) {
                $dataType = $this->getMethodParameterValidatorDefaults[$key];
                $this->getMethodParameterValidator($dataType, $data);
            } elseif (in_array($key ,$this->defaultAcceptableParameters)) {
                $this->handleDefaultParameters($key, $value);
            } else {
                $this->validatorDataCollector->setRejectedParameter([
                    $key => [
                        'value' => $value,
                        'parameterError' => 'This is an invalid parameter for this endpoint.'
                    ]
                ]);
            }
        }
    }

    protected function getMethodParameterValidator()
    {
        $parameterValidator = $this->parameterValidatorFactory->getFactoryItem($dataType);
        $this->validatorDataCollector = $parameterValidator->validate($this->validatorDataCollector, [$key => $value]);
    }

    protected function handleDefaultParameters($key, $value)
    {
        if (in_array($key, ['perpage', 'per_page'])) {
            $this->setPerPageParameter($value);
        } elseif ($key == 'page') {
            # code...
        } elseif (in_array($key, ['columndata', 'column_data'])) {
            # code...
        }
    }

    protected function setPerPageParameter($value)
    {
        // ! working here ****************************************************************8
        if ($this->isInt($value)) {
            $this->validatorDataCollector->setAcceptedParameter([
                "per_page" => [
                    'messsage' => "\"$this->endpoint\" is a valid endpoint for this API. You can also review available endpoints at " . $this->getIndexUrl(), 
                    'error' => false
                ]
            ]);
        } else {

        }
    }

    protected function isInt($value)
    {
        return is_numeric($value) && !str_contains($value, '.');
    }

    protected function setExtraData()
    {
        $this->validatorDataCollector->setExtraData(['acceptableParameters' => $this->acceptableParameters]);
    }

    protected function setValidatedMetaData()
    {
        $this->validatedMetaData = $this->validatorDataCollector->getAllData();
    }

    public function getValidatedQueryData() 
    {
        return $this->validatedMetaData;
    }
}