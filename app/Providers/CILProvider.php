<?php

namespace App\Providers;

use App\CoreIntegrationApi\CIL\ClauseBuilderFactory;
use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\ClassDataProvider;
use App\CoreIntegrationApi\DataTypeDeterminerFactory;

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
                $app->make(DataTypeDeterminerFactory::class),
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
