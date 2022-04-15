<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use Illuminate\Support\Facades\DB;

class ClassDataProvider 
{   
    protected $class;
    protected $classObject;
    protected $classTableName;
    protected $columnData;
    protected $availableParameters;

    public function __construct(DataTypeDeterminerFactory $dataTypeDeterminerFactory)
    {
        $this->dataTypeDeterminerFactory = $dataTypeDeterminerFactory;
    }

    public function setClass(string $class)
    {
        // dd($class, class_exists($class), is_subclass_of($class, 'Illuminate\Database\Eloquent\Model'));
        if (class_exists($class) && is_subclass_of($class, 'Illuminate\Database\Eloquent\Model')) {
            $this->class = $class;
            $this->classObject = new $class();
        } else {
            throw new \Exception('Class does not exist or is not a subclass of the Model class');
        }   
    }

    public function getClassPrimaryKeyName()
    {
        $primaryKeyName = $this->classObject->getKeyName() ? $this->classObject->getKeyName() : 'id';
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
        $this->addApiDataTypeToAcceptableParameters();
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
        foreach ($this->classDBData as $columnArray) {
            foreach ($columnArray as $column_data_name => $value) {
                $column_data_name = strtolower($column_data_name);
                $value = $value === Null ? $value : strtolower($value);

                $this->availableParameters['acceptableParameters'][$columnArray['Field']][$column_data_name] = $value; 
            }
        }
    }

    protected function addApiDataTypeToAcceptableParameters()
    {
        foreach ($this->availableParameters['acceptableParameters'] as $key => $columnArray) {
            $this->availableParameters['acceptableParameters'][$key]['api_data_type'] = $this->dataTypeDeterminerFactory->getFactoryItem($columnArray['type']);   
        }
    }

    public function getClassInfo()
    {
        $classInfo = [
            'primaryKeyName' => $this->getClassPrimaryKeyName(),
            'path' => $this->getClassPath(),
            'acceptableParameters' => $this->getClassAcceptableParameters(),
        ];
        return $classInfo;
    }

    
    // TODO: Test in other class
    // ClassDataProvider, also add it to RequestValidator
        // form info
}