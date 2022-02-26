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

    public function query($validatedQueryData)
    {
        $this->queryBuilder = $validatedQueryData['class']::query();

        foreach ($validatedQueryData['queryArguments'] as $data) {
            $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($data['dataType']);
            $this->queryBuilder = $clauseBuilder->build($this->queryBuilder, $data);
        }

        if (isset($validatedQueryData['acceptedParameters']['perPage'])) {
            $this->perPageParameter = $validatedQueryData['acceptedParameters']['perPage'];
        }
        
        return $this->queryBuilder->paginate($this->perPageParameter);
    }
}