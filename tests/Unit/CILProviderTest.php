<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\CIL\CILQueryAssembler;
use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\ResourceModelInfoProvider;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class CILProviderTest extends TestCase
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
            'CILQueryAssembler' => [CILQueryAssembler::class],
            'ResourceModelInfoProvider' => [ResourceModelInfoProvider::class],
            'EndpointValidator' => [EndpointValidator::class],
        ];
    }
}
