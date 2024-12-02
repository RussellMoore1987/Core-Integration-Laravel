<?php

namespace App\CoreIntegrationApi\CIL;

use App\CoreIntegrationApi\QueryAssembler;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory;

class CILQueryAssembler implements QueryAssembler
{
    protected $clauseBuilderFactory;
    protected $queryBuilder;
    protected $perPageParameter = 50;
    protected $pageParameter = 1;

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

        if (isset($validatedMetaData['acceptedParameters']['page'])) {
            $this->pageParameter = $validatedMetaData['acceptedParameters']['page'];
        }

        $this->isSingleIdRequest($validatedMetaData);

        return $this->queryBuilder->paginate(
            $this->perPageParameter,
            ['*'],
            'page',
            $this->pageParameter
        );
    }

    private function isSingleIdRequest($validatedMetaData): void // if id is a single request switch back to default parameters
    {
        $id = $validatedMetaData['endpointData']['resourceId'];
        if ($this->isSingleRequest($id)) {
            $this->perPageParameter = 50;
            if (isset($validatedMetaData['acceptedParameters']['page'])) {
                $this->pageParameter = 1;
            }
        }
    }

    private function isSingleRequest($resourceId): bool // @IsSingleIdRequest
    {
        return $resourceId && !str_contains($resourceId, ',') && !str_contains($resourceId, '::');
    }
}

// TODO: sql injection attack, test