<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\HttpMethodQueryResolverFactory\HttpMethodQueryResolvers\GetHttpMethodQueryResolver;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class HttpMethodQueryResolverProviderTest extends TestCase
{
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
            'GetHttpMethodQueryResolver' => [GetHttpMethodQueryResolver::class],
        ];
    }
}
