<?php

namespace App\CoreIntegrationApi\RestApi\RestQueryIndex;

class RestIndexHelper
{
    private MainHelper $mainHelper;
    private DocumentationHelper $docHelper;
    private RouteHelper $routeHelper;
    private array $index = [];

    public function __construct(MainHelper $mainHelper, DocumentationHelper $docHelper, RouteHelper $routeHelper)
    {
        $this->mainHelper = $mainHelper;
        $this->docHelper = $docHelper;
        $this->routeHelper = $routeHelper;
    }

    public function setMetaData(array &$validatedMetaData): void
    {
        $this->mainHelper->setMetaData($validatedMetaData);
        $this->routeHelper->setMetaData($validatedMetaData);
    }

    public function about(): void
    {
        $this->index['about'] = $this->mainHelper->getMainInformation();
    }

    public function generalDocumentation(): void
    {
        $this->index['generalDocumentation'] = $this->docHelper->getApiDocumentation();
    }

    public function quickRouteReference(): void
    {
        $this->index['quickRouteReference'] = $this->routeHelper->getQuickRoutes();
    }

    public function routes(): void
    {
        $this->index['routes'] = $this->routeHelper->getRoutes();
    }

    public function getIndex(): array
    {
        return $this->index;
    }
}
