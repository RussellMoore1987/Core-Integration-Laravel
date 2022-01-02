<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\ContextApi\ContextRequestProcessor;
use App\CoreIntegrationApi\ContextApi\ContextRequestValidator;
use App\CoreIntegrationApi\ContextApi\ContextQueryResolver;
use App\CoreIntegrationApi\ContextApi\ContextResponseBuilder;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ContextRequestProcessorProviderTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider classDataProvider
     */
    public function test_making_class_returns_correct_instance_of_its_self($classPath)
    {
        $newClass = App::make($classPath);

        $this->assertInstanceOf($classPath, $newClass);
    }

    public function classDataProvider()
    {
        return [
            'ContextRequestProcessor' => [ContextRequestProcessor::class],
            'ContextRequestValidator' => [ContextRequestValidator::class],
            'ContextQueryResolver' => [ContextQueryResolver::class],
            'ContextResponseBuilder' => [ContextResponseBuilder::class]
        ];
    }
}
