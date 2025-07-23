<?php

namespace App\CoreIntegrationApi\RestApi\RestQueryIndex;

use App\CoreIntegrationApi\ResourceModelInfoProvider;

// ! start here ************************* make test, look over DocumentationHelperTest.php, MainHelperTest.php

class RouteHelper
{
    private ResourceModelInfoProvider $resourceProvider;
    private array $validatedMetaData;
    private array $quickRoutes = [];
    private array $routes = [];

    public function __construct(ResourceModelInfoProvider $resourceProvider)
    {
        $this->resourceProvider = $resourceProvider;
    }

    public function setMetaData(array &$validatedMetaData): void
    {
        $this->validatedMetaData = $validatedMetaData;
    }

    public function getQuickRoutes(): array
    {
        $this->checkForRoutes();

        return $this->quickRoutes;
    }

    public function getRoutes(): array
    {
        $this->checkForRoutes();

        return $this->routes;
    }

    private function checkForRoutes(): void
    {
        if (empty($this->routes)) {
            $this->compileRoutes();
        }
    }

    private function compileRoutes(): void
    {
        $availableResourceEndpoints = config('coreintegration.availableResourceEndpoints') ?? [];
        $restrictedMethods = config('coreintegration.restrictedHttpMethods') ?? [];

        foreach ($availableResourceEndpoints as $resource => $resourceClass) {
            $route = $this->validatedMetaData['endpointData']['indexUrl'] . '/' . $resource;
            $this->quickRoutes[$resource]['url'] = $route;

            $availableMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
            $restrictedRouteMethods = config("coreintegration.routeOptions.{$resource}.restrictedHttpMethods") ?? [];
            $restrictedRouteMethods = array_merge($restrictedMethods, $restrictedRouteMethods);

            $availableMethods = array_diff($availableMethods, $restrictedRouteMethods);
            $this->quickRoutes[$resource]['availableMethods'] = implode(',', $availableMethods);

            $resourceInfo = $this->resourceProvider->getResourceInfo(new $resourceClass());
            unset($resourceInfo['primaryKeyName']);
            unset($resourceInfo['path']);

            foreach ($resourceInfo['acceptableParameters'] as $parameterName => $parameterArray) {
                unset($resourceInfo['acceptableParameters'][$parameterName]['field']);
                unset($resourceInfo['acceptableParameters'][$parameterName]['type']);
                unset($resourceInfo['acceptableParameters'][$parameterName]['key']);
                unset($resourceInfo['acceptableParameters'][$parameterName]['extra']);
                $resourceInfo['acceptableParameters'][$parameterName]['parameterDataType'] = $parameterArray['apiDataType'];
                unset($resourceInfo['acceptableParameters'][$parameterName]['apiDataType']);
                unset($resourceInfo['acceptableParameters'][$parameterName]['defaultValidationRules']);
            }

            $this->routes[$resource] = $resourceInfo;

            $routeAuth = (bool) config("coreintegration.routeOptions.{$resource}.authenticationToken");
            if ($routeAuth) {
                $this->routes[$resource]['routeSpecificAuthentication'] = true;
                $this->quickRoutes[$resource]['routeSpecificAuthentication'] = true;
            }
        }
    }
}
