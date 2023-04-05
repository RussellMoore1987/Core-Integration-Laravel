<?php

namespace App\Providers;

use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory;
use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\ResourceModelInfoProvider;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\GetRequestMethodTypeValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\DefaultGetParameterValidator;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviderFactory;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use Illuminate\Support\ServiceProvider;

class CILProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindCILQueryAssembler();
        $this->bindResourceModelInfoProvider();
        $this->bindGetRequestMethodTypeValidator();
        $this->bindEndpointValidator();
    }

    private function bindCILQueryAssembler() {
        $this->app->bind(CILQueryAssembler::class, function ($app) {
            return new CILQueryAssembler(
                $app->make(ClauseBuilderFactory::class),
            );
        });
    }

    private function bindResourceModelInfoProvider() {
        $this->app->bind(ResourceModelInfoProvider::class, function ($app) {
            return new ResourceModelInfoProvider(
                $app->make(ResourceParameterInfoProviderFactory::class),
            );
        });
    }

    private function bindGetRequestMethodTypeValidator() {
        $this->app->bind(GetRequestMethodTypeValidator::class, function ($app) {
            return new GetRequestMethodTypeValidator(
                $app->make(ParameterValidatorFactory::class),
                $app->make(DefaultGetParameterValidator::class),
            );
        });
    }

    private function bindEndpointValidator() {
        $this->app->bind(EndpointValidator::class, function ($app) {
            return new EndpointValidator(
                $app->make(ResourceModelInfoProvider::class),
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
