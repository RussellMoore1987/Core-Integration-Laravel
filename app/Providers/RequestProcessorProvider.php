<?php

namespace App\Providers;

use App\CoreIntegrationApi\ContextApi\RestRequestProcessor;
use App\CoreIntegrationApi\RestApi\ContextRequestProcessor;
use App\CoreIntegrationApi\CILRequestRouter;

use Illuminate\Support\ServiceProvider;

class RequestProcessorProvider extends ServiceProvider 
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindCILRequestRouter();
    }

    private function bindCILRequestRouter() {
        $this->app->bind(CILRequestRouter::class, function ($app) {
            return new CILRequestRouter(
                $app->make(RestRequestProcessor::class),
                $app->make(ContextRequestProcessor::class),
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
