<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResourceModelInfoProvider
{
    protected $resourceObject;
    protected $resourceClassPath;
    protected $availableParameters = [];

    public function __construct(ResourceParameterInfoProviderFactory $resourceParameterInfoProviderFactory)
    {
        $this->resourceParameterInfoProviderFactory = $resourceParameterInfoProviderFactory;
    }

    public function getResourceInfo(Model $class) : array
    {
        $this->resourceObject = $class;
        $this->resourceClassPath = get_class($class);
        
        return array_merge(
            [
                'primaryKeyName' => $this->getResourcePrimaryKeyName(),
                'path' => $this->resourceClassPath,
            ],
            $this->getResourceAcceptableParameters()
        );
    }

    protected function getResourcePrimaryKeyName() : string
    {
        return $this->resourceObject->getKeyName() ?? 'id';
    }

    protected function getResourceAcceptableParameters() : array
    {
        $resourceTableName = $this->resourceObject->gettable();
        $this->getAcceptableParameters($resourceTableName);
        return $this->availableParameters;
    }

    protected function getAcceptableParameters(string $resourceTableName) : void
    {
        $resourceColumnData = $this->arrayOfObjectsToArrayOfArrays(DB::select("SHOW COLUMNS FROM {$resourceTableName}"));
        $this->setAcceptableParameters($resourceColumnData);
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

    protected function setAcceptableParameters(array $resourceColumnData) : void
    {
        foreach ($resourceColumnData as $columnAttributeArray) {
            foreach ($columnAttributeArray as $attributeName => $value) {
                $attributeName = strtolower($attributeName);
                $value = $value === null ? $value : strtolower($value);

                $this->availableParameters['acceptableParameters'][$columnAttributeArray['Field']][$attributeName] = $value;
            }
        }
    }

    protected function addAdditionalInfoToAcceptableParameters() : void
    {
        foreach ($this->availableParameters['acceptableParameters'] as $parameterName => $parameterAttributeArray) {
            $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($parameterAttributeArray['type']);
            $parameterData = $resourceParameterInfoProvider->getData($parameterAttributeArray, $this->resourceObject->formData ?? []);

            $this->availableParameters['acceptableParameters'][$parameterName]['apiDataType'] = $parameterData['apiDataType'];
            $this->availableParameters['acceptableParameters'][$parameterName]['defaultValidationRules'] = $parameterData['defaultValidationRules'];
            $this->availableParameters['acceptableParameters'][$parameterName]['formData'] = $parameterData['formData'];
        }
    }
}