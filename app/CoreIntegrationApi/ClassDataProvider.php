<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviderFactory;
use Illuminate\Support\Facades\DB;

class ClassDataProvider 
{   
    protected $class;
    protected $classObject;
    protected $classTableName;
    protected $columnData;
    protected $availableParameters;

    public function __construct(ParameterDataProviderFactory $parameterDataProviderFactory)
    {
        $this->parameterDataProviderFactory = $parameterDataProviderFactory;
    }

    public function setClass(string $class)
    {
        if (class_exists($class) && is_subclass_of($class, 'Illuminate\Database\Eloquent\Model')) {
            $this->class = $class;
            $this->classObject = new $class();
        } else {
            throw new \Exception('Class does not exist or is not a subclass of the Model class');
        }   
    }

    public function getClassPrimaryKeyName()
    {
        $primaryKeyName = $this->classObject->getKeyName() ?? 'id';
        return $primaryKeyName;
    }

    public function getClassPath()
    {
        return $this->class;
    }

    public function getClassAcceptableParameters()
    {
        $this->classTableName = $this->classObject->gettable();
        $this->getAcceptableParameters();
        return $this->availableParameters;
    }

    protected function getAcceptableParameters()
    {
        $this->columnData = $this->arrayOfObjectsToArrayOfArrays(DB::select("SHOW COLUMNS FROM {$this->classTableName}"));
        $this->setAcceptableParameters();
        $this->addFormDataToAcceptableParameters();
        $this->availableParameters['availableMethodCalls'] = $this->classObject->availableMethodCalls ?? [];
        $this->availableParameters['availableIncludes'] = $this->classObject->availableIncludes ?? [];
    }

    protected function arrayOfObjectsToArrayOfArrays(array $arrayOfObjects)
    {
        foreach ($arrayOfObjects as $object) {
            $arrayOfArrays[] = (array) $object;
        }

        return $arrayOfArrays;
    }

    protected function setAcceptableParameters()
    {
        foreach ($this->columnData as $columnArray) {
            foreach ($columnArray as $column_data_name => $value) {
                $column_data_name = strtolower($column_data_name);
                $value = $value === Null ? $value : strtolower($value);

                $this->availableParameters['acceptableParameters'][$columnArray['Field']][$column_data_name] = $value; 
            }
        }
    }

    protected function addFormDataToAcceptableParameters()
    {
        // ! working here ******************************************************** date parameterDataProvider formData, validation data as well, and date and int end to end testing API, update uml diagram with cil trait
        // https://laravel.com/docs/8.x/validation#manually-creating-validators
        // TODO:
        // form info
        // Test class formData, and db formData
            // min
            // max
            // minlength
            // maxlength
            // required
            // unique
            // others laravel validation rules
        foreach ($this->availableParameters['acceptableParameters'] as $key => $columnArray) {
            $parameterFormDataProvider = $this->parameterDataProviderFactory->getFactoryItem($columnArray['type']);
            $parameterData = $parameterFormDataProvider->getData($columnArray['type'], $key, $this->classObject);
            $this->availableParameters['acceptableParameters'][$key]['api_data_type'] = $parameterData['apiDataType'];
            $this->availableParameters['acceptableParameters'][$key]['validationRules'] = $parameterData['validationRules'];
            $this->availableParameters['acceptableParameters'][$key]['formData'] = $parameterData['formData'];
        }
    }

    public function getClassInfo()
    {
        $classInfo = [
            'primaryKeyName' => $this->getClassPrimaryKeyName(),
            'path' => $this->getClassPath(),
            'classParameterOptions' => $this->getClassAcceptableParameters(),
        ];
        return $classInfo;
    }
}