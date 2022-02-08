<?php

namespace App\Providers;

use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use App\CoreIntegrationApi\RestApi\RestRequestProcessor;
use App\CoreIntegrationApi\RestApi\RestRequestValidator;
use App\CoreIntegrationApi\RestApi\RestResponseBuilder;
use App\CoreIntegrationApi\RestApi\RestQueryIndex;
use App\CoreIntegrationApi\RestApi\RestQueryResolver;
use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\CIL\CILQueryDeleter;
use App\CoreIntegrationApi\CIL\CILQueryPersister;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class RestRequestProcessorProvider extends ServiceProvider 
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRequestDataPrepper();
        $this->bindRequestValidator();
        $this->bindQueryResolver();
        $this->bindResponseBuilder();
        $this->bindRequestProcessor();
    }

    private function bindRequestDataPrepper() {
        $this->app->bind(RestRequestDataPrepper::class, function ($app) {
            return new RestRequestDataPrepper(
                $app->make(Request::class),
            );
        });
    }

    private function bindRequestValidator() {
        $this->app->bind(RestRequestValidator::class, function ($app) {
            return new RestRequestValidator(
                $app->make(RestRequestDataPrepper::class),
            );
        });
    }

    private function bindQueryResolver() {
        $this->app->bind(RestQueryResolver::class, function ($app) {
            return new RestQueryResolver(
                $app->make(CILQueryAssembler::class),
                $app->make(CILQueryPersister::class),
                $app->make(RestQueryIndex::class),
                $app->make(CILQueryDeleter::class),
            );
        });
    }

    private function bindResponseBuilder() {
        $this->app->bind(RestResponseBuilder::class, function ($app) {
            return new RestResponseBuilder;
        });
    }

    private function bindRequestProcessor() {
        $this->app->bind(RestRequestProcessor::class, function ($app) {
            return new RestRequestProcessor(
                $app->make(RestRequestValidator::class),
                $app->make(RestQueryResolver::class),
                $app->make(RestResponseBuilder::class),
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
