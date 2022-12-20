<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// ! Start here ****************************************************************** readability
// TODO: may need to change the name of this class as this class specifically gets things for model resources
class ResourceModelInfoProvider
{
    protected $resourceObject;
    protected $classPath;
    protected $resourceTableName;
    protected $columnData;
    protected $availableParameters = [];

    public function __construct(ResourceParameterInfoProviderFactory $resourceParameterInfoProviderFactory)
    {
        $this->resourceParameterInfoProviderFactory = $resourceParameterInfoProviderFactory;
    }

    public function setResource(Model $class) : void
    {
        $this->resourceObject = $class;
        $this->classPath = get_class($class);
    }

    public function getResourcePrimaryKeyName() : string
    {
        return $this->resourceObject->getKeyName() ?? 'id';
    }

    public function getResourceClassPath() : string
    {
        return $this->classPath;
    }

    public function getResourceAcceptableParameters() : array
    {
        $this->resourceTableName = $this->resourceObject->gettable();
        $this->getAcceptableParameters();
        return $this->availableParameters;
    }

    protected function getAcceptableParameters() : void
    {
        $this->columnData = $this->arrayOfObjectsToArrayOfArrays(DB::select("SHOW COLUMNS FROM {$this->resourceTableName}"));
        $this->setAcceptableParameters();
        $this->addAdditionalInfoToAcceptableParameters();
        $this->availableParameters['availableMethodCalls'] = $this->resourceObject->availableMethodCalls ?? [];
        $this->availableParameters['availableIncludes'] = $this->resourceObject->availableIncludes ?? [];
    }

    protected function arrayOfObjectsToArrayOfArrays(array $arrayOfObjects) : array
    {
        foreach ($arrayOfObjects as $object) {
            $arrayOfArrays[] = (array) $object;
        }

        return $arrayOfArrays;
    }

    protected function setAcceptableParameters() : void
    {
        foreach ($this->columnData as $columnArray) {
            foreach ($columnArray as $columnDataName => $value) {
                $columnDataName = strtolower($columnDataName);
                $value = $value === null ? $value : strtolower($value);

                $this->availableParameters['acceptableParameters'][$columnArray['Field']][$columnDataName] = $value;
            }
        }
    }

    protected function addAdditionalInfoToAcceptableParameters() : void
    {
        foreach ($this->availableParameters['acceptableParameters'] as $key => $columnArray) {
            $parameterFormDataProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($columnArray['type']);
            $parameterData = $parameterFormDataProvider->getData($columnArray, $this->resourceObject);

            $this->availableParameters['acceptableParameters'][$key]['api_data_type'] = $parameterData['apiDataType'];
            $this->availableParameters['acceptableParameters'][$key]['defaultValidationRules'] = $parameterData['defaultValidationRules'];
            $this->availableParameters['acceptableParameters'][$key]['formData'] = $parameterData['formData'];
        }
    }

    public function getResourceInfo() : array
    {
        return array_merge(
            [
                'primaryKeyName' => $this->getResourcePrimaryKeyName(),
                'path' => $this->classPath,
            ],
            $this->getResourceAcceptableParameters()
        );
    }
}