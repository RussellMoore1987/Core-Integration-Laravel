<?php

namespace App\Providers;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\GetHttpMethodQueryResolver;
use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\RestApi\RestQueryIndex;
use Illuminate\Support\ServiceProvider;

class HttpMethodQueryResolverProvider extends ServiceProvider 
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
        $this->app->bind(GetHttpMethodQueryResolver::class, function ($app) {
            return new GetHttpMethodQueryResolver(
                $app->make(CILQueryAssembler::class),
                $app->make(RestQueryIndex::class),
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
