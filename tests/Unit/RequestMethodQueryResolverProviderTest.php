<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RequestMethodQueryResolverFactory\RequestMethodQueryResolvers\GetRequestMethodQueryResolver;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class RequestMethodQueryResolverProviderTest extends TestCase
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
            'GetRequestMethodQueryResolver' => [GetRequestMethodQueryResolver::class],
        ];
    }
}
