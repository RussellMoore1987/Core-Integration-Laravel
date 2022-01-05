<?php

namespace App\Providers;

use App\CoreIntegrationApi\ContextApi\ContextRequestDataPrepper;
use App\CoreIntegrationApi\ContextApi\ContextRequestProcessor;
use App\CoreIntegrationApi\ContextApi\ContextRequestValidator;
use App\CoreIntegrationApi\ContextApi\ContextResponseBuilder;
use App\CoreIntegrationApi\ContextApi\ContextQueryIndex;
use App\CoreIntegrationApi\ContextApi\ContextQueryResolver;
use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\CIL\CILQueryDeleter;
use App\CoreIntegrationApi\CIL\CILQueryPersister;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ContextRequestProcessorProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRequestValidator();
        $this->bindQueryResolver();
        $this->bindResponseBuilder();
        $this->bindRequestProcessor();
    }

    private function bindRequestValidator() {
        $this->app->bind(ContextRequestValidator::class, function ($app) {
            return new ContextRequestValidator(
                $app->make(ContextRequestDataPrepper::class),
            );
        });
    }

    private function bindQueryResolver() {
        $this->app->bind(ContextQueryResolver::class, function ($app) {
            return new ContextQueryResolver(
                $app->make(CILQueryAssembler::class),
                $app->make(CILQueryPersister::class),
                $app->make(ContextQueryIndex::class),
                $app->make(CILQueryDeleter::class),
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