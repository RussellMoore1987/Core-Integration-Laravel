<?php

namespace App\Providers;

use App\CoreIntegrationApi\ContextApi\ContextRequestDataPrepper;
use App\CoreIntegrationApi\ContextApi\ContextRequestProcessor;
use App\CoreIntegrationApi\ContextApi\ContextRequestValidator;
use App\CoreIntegrationApi\ContextApi\ContextResponseBuilder;
use App\CoreIntegrationApi\ContextApi\ContextQueryResolver;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\ClassDataProvider;
use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolverFactory;
use App\CoreIntegrationApi\HttpMethodTypeValidatorFactory\HttpMethodTypeValidatorFactory;
use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;

class ContextRequestProcessorProvider extends ServiceProvider 
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRequestValidator();
        $this->bindRequestDataPrepper();
        $this->bindQueryResolver();
        $this->bindResponseBuilder();
        $this->bindRequestProcessor();
    }

   

    private function bindRequestValidator() {
        $this->app->bind(ContextRequestValidator::class, function ($app) {
            return new ContextRequestValidator(
                $app->make(ContextRequestDataPrepper::class),
                $app->make(ValidatorDataCollector::class),
                $app->make(ClassDataProvider::class),
                $app->make(HttpMethodTypeValidatorFactory::class),
            );
        });
    }

    private function bindRequestDataPrepper() {
        $this->app->bind(ContextRequestDataPrepper::class, function ($app) {
            return new ContextRequestDataPrepper(
                $app->make(Request::class),
            );
        });
    }

    private function bindQueryResolver() {
        $this->app->bind(ContextQueryResolver::class, function ($app) {
            return new ContextQueryResolver(
                $app->make(HttpMethodQueryResolverFactory::class),
            );
        });
    }

    private function bindResponseBuilder() {
        $this->app->bind(ContextResponseBuilder::class, function ($app) {
            return new ContextResponseBuilder;
        });
    }

    private function bindRequestProcessor() {
        $this->app->bind(ContextRequestProcessor::class, function ($app) {
            return new ContextRequestProcessor(
                $app->make(ContextRequestValidator::class),
                $app->make(ContextQueryResolver::class),
                $app->make(ContextResponseBuilder::class),
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
