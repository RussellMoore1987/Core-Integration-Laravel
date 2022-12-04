<?php

namespace App\Providers;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\GetRequestMethodQueryResolver;
use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\RestApi\RestQueryIndex;
use Illuminate\Support\ServiceProvider;

class RequestMethodQueryResolverProvider extends ServiceProvider 
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRequestDataPrepper();
    }

    private function bindRequestDataPrepper() {
        $this->app->bind(GetRequestMethodQueryResolver::class, function ($app) {
            return new GetRequestMethodQueryResolver(
                $app->make(CILQueryAssembler::class),
                $app->make(RestQueryIndex::class), // Needs to be able to switch with context
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
