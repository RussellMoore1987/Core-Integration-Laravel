<?php

namespace App\CoreIntegrationApi;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResourceModelInfoProvider
{
    protected $resourceParameterInfoProviderFactory;
    protected $resourceFormData;
    protected $availableParameters = [];

    public function __construct(ResourceParameterInfoProviderFactory $resourceParameterInfoProviderFactory)
    {
        $this->resourceParameterInfoProviderFactory = $resourceParameterInfoProviderFactory;
    }

    public function getResourceInfo(Model $resourceObject): array
    {
        $this->resourceFormData = $resourceObject->formData ?? [];
        
        return [
            'primaryKeyName' => $resourceObject->getKeyName() ?? 'id',
            'path' => get_class($resourceObject),
            'acceptableParameters' => $this->getResourceAcceptableParameters($resourceObject),
            'availableMethodCalls' => $resourceObject->availableMethodCalls ?? [],
            'availableIncludes' => $resourceObject->availableIncludes ?? [],
        ];
    }

    protected function getResourceAcceptableParameters($resourceObject): array
    {
        $resourceTableName = $resourceObject->gettable();
        $this->getAcceptableParameters($resourceTableName);
        return $this->availableParameters;
    }

    protected function getAcceptableParameters(string $resourceTableName): void
    {
        $resourceColumnData = $this->arrayOfObjectsToArrayOfArrays(DB::select("SHOW COLUMNS FROM {$resourceTableName}"));
        $this->setAcceptableParameters($resourceColumnData);
        $this->addAdditionalInfoToAcceptableParameters();
    }

    protected function arrayOfObjectsToArrayOfArrays(array $arrayOfObjects): array
    {
        foreach ($arrayOfObjects as $object) {
            $arrayOfArrays[] = (array) $object;
        }

        return $arrayOfArrays;
    }

    protected function setAcceptableParameters(array $resourceColumnData): void
    {
        foreach ($resourceColumnData as $columnAttributeArray) {
            foreach ($columnAttributeArray as $attributeName => $value) {
                $attributeName = strtolower($attributeName);
                $value = $value === null ? $value : strtolower($value);

                $this->availableParameters[$columnAttributeArray['Field']][$attributeName] = $value;
            }
        }
    }

    protected function addAdditionalInfoToAcceptableParameters(): void
    {
        foreach ($this->availableParameters as $parameterName => $parameterAttributeArray) {
            $resourceParameterInfoProvider = $this->resourceParameterInfoProviderFactory->getFactoryItem($parameterAttributeArray['type']);
            $parameterData = $resourceParameterInfoProvider->getData($parameterAttributeArray, $this->resourceFormData);

            $this->availableParameters[$parameterName] += $parameterData; // array merge
        }
    }
}