<?php

namespace App\Providers;

use App\CoreIntegrationApi\ContextApi\RestRequestProcessor;
use App\CoreIntegrationApi\RestApi\ContextRequestProcessor;
use App\CoreIntegrationApi\RequestProcessRouter;

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
        $this->bindRequestProcessRouter();
    }

    private function bindRequestProcessRouter() {
        $this->app->bind(RequestProcessRouter::class, function ($app) {
            return new RequestProcessRouter(
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
