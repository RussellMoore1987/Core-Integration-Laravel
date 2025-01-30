<?php

namespace App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\RequestMethodQueryResolver;
use App\CoreIntegrationApi\QueryAssembler;
use App\CoreIntegrationApi\QueryIndex;

class GetRequestMethodQueryResolver implements RequestMethodQueryResolver
{
    protected $queryAssembler;
    protected $queryIndex;
    protected $validatedMetaData;
    protected $queryResult;

    public function __construct(QueryAssembler $queryAssembler, QueryIndex $queryIndex)
    {
        $this->queryAssembler = $queryAssembler;
        $this->queryIndex = $queryIndex;
    }

    public function resolveQuery($validatedMetaData)
    {
        $this->validatedMetaData = $validatedMetaData;

        $this->checkResourceColumnData(); // get, has, set*** if statement
        $this->checkIndex();
        $this->checkFormData();
        $this->checkGetRequest();

        return $this->queryResult; // TODO: test if casting to an array brakes anything, if not make array the correct return
    }

    protected function checkResourceColumnData(): void
    {
        if (isset($this->validatedMetaData['acceptedParameters']['columnData'])) {
            foreach ($this->validatedMetaData['resourceInfo']['acceptableParameters'] as $columnName => $columnArray) {
                $this->queryResult['availableResourceParameters'][$columnName] = $columnArray['apiDataType'];
            }
            $this->queryResult['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the ApiDocumentation section.',
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
            $this->queryResult['acceptedParameters'] = $this->validatedMetaData['acceptedParameters'];
        }
    }

    protected function checkIndex(): void
    {
        if (!$this->queryResult && $this->validatedMetaData['endpointData']['resource'] == 'index') {
            $this->queryResult = $this->queryIndex->get($this->validatedMetaData);
        }
    }

    protected function checkFormData(): void
    {
        if (!$this->queryResult && isset($this->validatedMetaData['acceptedParameters']['formData'])) {
            foreach ($this->validatedMetaData['resourceInfo']['acceptableParameters'] as $parameterName => $parameterArray) {
                $this->queryResult['formData'][$parameterName] = $parameterArray['formData'];
            }
            $this->queryResult['acceptedParameters'] = $this->validatedMetaData['acceptedParameters'];
        }
    }

    protected function checkGetRequest(): void
    {
        if (!$this->queryResult && strtolower($this->validatedMetaData['endpointData']['requestMethod']) == 'get') {
            $this->queryResult = $this->queryAssembler->query($this->validatedMetaData);
        }
    }
}