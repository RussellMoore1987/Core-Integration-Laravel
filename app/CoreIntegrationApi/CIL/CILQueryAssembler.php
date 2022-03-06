<?php

namespace App\CoreIntegrationApi\CIL;

use App\CoreIntegrationApi\QueryAssembler;
use App\CoreIntegrationApi\CIL\ClauseBuilderFactory;

class CILQueryAssembler implements QueryAssembler
{
    protected $clauseBuilderFactory;
    protected $queryBuilder;
    protected $perPageParameter = 50;

    function __construct(ClauseBuilderFactory $clauseBuilderFactory) 
    {
        $this->clauseBuilderFactory = $clauseBuilderFactory;
    }

    public function query($validatedMetaData)
    {
        $this->queryBuilder = $validatedMetaData['endpointData']['class']::query();

        foreach ($validatedMetaData['queryArguments'] as $data) {
            $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($data['dataType']);
            $this->queryBuilder = $clauseBuilder->build($this->queryBuilder, $data);
        }

        if (isset($validatedMetaData['acceptedParameters']['per_page'])) {
            $this->perPageParameter = $validatedMetaData['acceptedParameters']['per_page'];
        }

        return $this->queryBuilder->paginate($this->perPageParameter);
    }
}