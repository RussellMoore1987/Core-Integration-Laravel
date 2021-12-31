<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestRequestProcessor;
use App\CoreIntegrationApi\RestApi\RestRequestValidator;
use App\CoreIntegrationApi\RestApi\RestQueryResolver;
use App\CoreIntegrationApi\RestApi\RestResponseBuilder;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class RestRequestProcessorProviderTest extends TestCase
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
            'RestRequestProcessor' => [RestRequestProcessor::class],
            'RestRequestValidator' => [RestRequestValidator::class],
            'RestQueryResolver' => [RestQueryResolver::class],
            'RestResponseBuilder' => [RestResponseBuilder::class]
        ];
    }
}
