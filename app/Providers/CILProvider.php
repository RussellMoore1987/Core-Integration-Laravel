<?php

namespace App\Providers;

use App\CoreIntegrationApi\ClauseBuilderFactory\ClauseBuilderFactory;
use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\ClassDataProvider;
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
        $this->bindClassDataProvider();
        $this->bindGetHttpMethodTypeValidator();
    }

    private function bindCILQueryAssembler() {
        $this->app->bind(CILQueryAssembler::class, function ($app) {
            return new CILQueryAssembler(
                $app->make(ClauseBuilderFactory::class),
            );
        });
    }

    private function bindClassDataProvider() {
        $this->app->bind(ClassDataProvider::class, function ($app) {
            return new ClassDataProvider(
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
