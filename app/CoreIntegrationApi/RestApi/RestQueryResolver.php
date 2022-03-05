<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\QueryResolver;

class RestQueryResolver extends QueryResolver
{
    // uses serves a provider for dependency injection, Located app\Providers\RestRequestProcessorProvider.php

    protected $validatedMetaData;
    protected $queryResult;
    
    public function resolve($validatedMetaData)
    {
        $this->validatedMetaData = $validatedMetaData;
        
        dd($this->queryResult, $this->validatedMetaData);
        
        if (!$this->validatedMetaData['endpointData']['endpointError']) {
            $this->checkEndpointColumnData();
            $this->checkIndex();
            $this->checkFormData();
            $this->checkGetRequest();
            $this->checkPersistRequest();
            $this->checkDeleteRequest();
        }

        dd($this->queryResult, $this->validatedMetaData);

        return $this->queryResult;
    }

    protected function checkEndpointColumnData()
    {
        if (isset($this->validatedMetaData['acceptedParameters']['column_data'])) {
            foreach ($this->validatedMetaData['extraData']['acceptableParameters'] as $columnName => $columnArray) {
                $this->queryResult['availableEndpointParameters'][$columnName] = $columnArray['api_data_type'];
            }
            $this->queryResult['info'] = [
                'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the ApiDocumentation section.', 
                'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
            ];
        }
    }

    protected function checkIndex()
    {
        if (!$this->queryResult && $this->validatedMetaData['endpointData']['endpoint'] == 'index') {
            $this->queryResult = $this->queryIndex->get();   
        }
    }

    protected function checkFormData()
    {
        if (!$this->queryResult && isset($this->validatedMetaData['acceptedParameters']['form_data'])) {
            // TODO: get form data
            $this->queryResult = 'form data';   
        }
    }

    protected function checkGetRequest()
    {
        if (!$this->queryResult && strtolower($this->validatedMetaData['endpointData']['httpMethod']) == 'get') {
            $this->queryResult = $this->queryAssembler->query($this->validatedMetaData);
        }
    }

    protected function checkPersistRequest()
    {
        if (!$this->queryResult && in_array(strtolower($this->validatedMetaData['endpointData']['httpMethod']), ['post', 'put', 'patch'])) {
            $this->queryResult = $this->queryPersister->persist($this->validatedMetaData); 
        }
    }

    protected function checkDeleteRequest()
    {
        if (!$this->queryResult && strtolower($this->validatedMetaData['endpointData']['httpMethod']) == 'delete') {
            $this->queryResult = $this->queryDeleter->delete($this->validatedMetaData);
        }
    }
}

// TODO: make it so you can send in snake_case or camelCase as a parameter
// TODO: make constant casing on out put