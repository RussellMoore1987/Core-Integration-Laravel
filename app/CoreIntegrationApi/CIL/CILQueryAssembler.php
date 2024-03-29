<?php

namespace App\CoreIntegrationApi\CIL;

use App\CoreIntegrationApi\QueryAssembler;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory;

class CILQueryAssembler implements QueryAssembler
{
    protected $clauseBuilderFactory;
    protected $queryBuilder;
    protected $perPageParameter = 50;

    public function __construct(ClauseBuilderFactory $clauseBuilderFactory)
    {
        $this->clauseBuilderFactory = $clauseBuilderFactory;
    }

    public function query($validatedMetaData)
    {
        $this->queryBuilder = $validatedMetaData['resourceInfo']['path']::query();

        foreach ($validatedMetaData['queryArguments'] as $data) {
            $clauseBuilder = $this->clauseBuilderFactory->getFactoryItem($data['dataType']);
            $this->queryBuilder = $clauseBuilder->build($this->queryBuilder, $data);
        }

        if (isset($validatedMetaData['acceptedParameters']['perPage'])) {
            $this->perPageParameter = $validatedMetaData['acceptedParameters']['perPage'];
        }

        return $this->queryBuilder->paginate($this->perPageParameter);
    }
}

// TODO: sql injection attack, test