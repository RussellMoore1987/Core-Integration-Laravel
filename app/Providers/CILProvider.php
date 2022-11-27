<?php

namespace App\Providers;

use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory;
use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\ResourceDataProvider;
use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidators\GetHttpMethodTypeValidator;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviderFactory;
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
        $this->bindResourceDataProvider();
        $this->bindGetHttpMethodTypeValidator();
    }

    private function bindCILQueryAssembler() {
        $this->app->bind(CILQueryAssembler::class, function ($app) {
            return new CILQueryAssembler(
                $app->make(ClauseBuilderFactory::class),
            );
        });
    }

    private function bindResourceDataProvider() {
        $this->app->bind(ResourceDataProvider::class, function ($app) {
            return new ResourceDataProvider(
                $app->make(ParameterDataProviderFactory::class),
            );
        });
    }

    private function bindGetHttpMethodTypeValidator() {
        $this->app->bind(GetHttpMethodTypeValidator::class, function ($app) {
            return new GetHttpMethodTypeValidator(
                $app->make(ParameterValidatorFactory::class),
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
