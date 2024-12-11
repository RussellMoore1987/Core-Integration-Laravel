<?php

namespace App\CoreIntegrationApi\CIL;

use App\CoreIntegrationApi\QueryAssembler;
use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory;

class CILQueryAssembler implements QueryAssembler
{
    const DEFAULT_PER_PAGE = 50;
    const DEFAULT_PAGE = 1;

    protected $clauseBuilderFactory;
    protected $queryBuilder;
    protected $perPageParameter = self::DEFAULT_PER_PAGE;
    protected $pageParameter = self::DEFAULT_PAGE;

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
            ['*'], // columns
            'page', // pageName
            $this->pageParameter
        );
    }

    // if id is a single request, if there, switch back to default page and perPage parameters
    private function isSingleIdRequest($validatedMetaData): void
    {
        $id = $validatedMetaData['endpointData']['resourceId'];
        if ($this->isItASingleIdRequest($id)) {
            $this->perPageParameter = self::DEFAULT_PER_PAGE;
            if (isset($validatedMetaData['acceptedParameters']['page'])) {
                $this->pageParameter = self::DEFAULT_PAGE;
            }
        }
    }

    private function isItASingleIdRequest($resourceId): bool // @IsSingleIdRequest
    {
        return $resourceId && !str_contains($resourceId, ',') && !str_contains($resourceId, '::');
    }
}

// TODO: sql injection attack, test