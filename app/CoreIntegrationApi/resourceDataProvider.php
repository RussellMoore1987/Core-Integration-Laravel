<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class resourceDataProvider
{
    protected $classPath;
    protected $resourceObject;
    protected $classTableName;
    protected $columnData;
    protected $availableParameters;

    public function __construct(ParameterDataProviderFactory $parameterDataProviderFactory)
    {
        $this->parameterDataProviderFactory = $parameterDataProviderFactory;
    }

    public function setResource(Model $class)
    {
        $this->resourceObject = $class;
        $this->classPath = get_class($class);
    }

    public function getClassPrimaryKeyName()
    {
        $primaryKeyName = $this->resourceObject->getKeyName() ?? 'id';
        return $primaryKeyName;
    }

    public function getClassPath()
    {
        return $this->classPath;
    }

    public function getClassAcceptableParameters()
    {
        $this->classTableName = $this->resourceObject->gettable();
        $this->getAcceptableParameters();
        return $this->availableParameters;
    }

    protected function getAcceptableParameters()
    {
        $this->columnData = $this->arrayOfObjectsToArrayOfArrays(DB::select("SHOW COLUMNS FROM {$this->classTableName}"));
        $this->setAcceptableParameters();
        $this->addAdditionalInfoToAcceptableParameters();
        $this->availableParameters['availableMethodCalls'] = $this->resourceObject->availableMethodCalls ?? [];
        $this->availableParameters['availableIncludes'] = $this->resourceObject->availableIncludes ?? [];
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

    protected function addAdditionalInfoToAcceptableParameters()
    {
        foreach ($this->availableParameters['acceptableParameters'] as $key => $columnArray) {
            $parameterFormDataProvider = $this->parameterDataProviderFactory->getFactoryItem($columnArray['type']);
            $parameterData = $parameterFormDataProvider->getData($columnArray, $this->resourceObject);

            $this->availableParameters['acceptableParameters'][$key]['api_data_type'] = $parameterData['apiDataType'];
            $this->availableParameters['acceptableParameters'][$key]['defaultValidationRules'] = $parameterData['defaultValidationRules'];
            $this->availableParameters['acceptableParameters'][$key]['formData'] = $parameterData['formData'];
        }
    }

    public function getResourceInfo()
    {
        $resourceInfo = array_merge(
            [
            'primaryKeyName' => $this->getClassPrimaryKeyName(),
            'path' => $this->getClassPath(),
            ],
            $this->getClassAcceptableParameters()
        );
        return $resourceInfo;
    }
}