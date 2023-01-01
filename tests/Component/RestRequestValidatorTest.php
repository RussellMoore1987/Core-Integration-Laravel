<?php

namespace Tests\Component;

use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;
use App\CoreIntegrationApi\RestApi\RestRequestValidator;
use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class RestRequestValidatorTest extends TestCase
{
    /**
     * @dataProvider requestMethodProvider
     * @group allRequestMethods
     */
    public function test_RestRequestValidator_returns_expected_result_array_with_correct_keys($requestMethod) : void
    {
        $validatedMetaData = $this->getRestRequestValidatorResponse([
            'resource' => 'projects',
            'id' => 33,
            'title' => 'Test Project',
        ], 'api/v1/projects', $requestMethod);
        
        // * asserting structure, details tested in other classes like ResourceModelInfoProvider and ResourceParameterInfoProviders
        $this->assertArrayHasKey('endpointData', $validatedMetaData);
        $this->assertArrayHasKey('resourceInfo', $validatedMetaData);
        $this->assertArrayHasKey('rejectedParameters', $validatedMetaData);
        $this->assertArrayHasKey('acceptedParameters', $validatedMetaData);
        $this->assertArrayHasKey('queryArguments', $validatedMetaData);
        $this->assertEquals(5, count($validatedMetaData)); // test fails if not up dated

        $this->assertArrayHasKey('primaryKeyName', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('path', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('acceptableParameters', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('availableMethodCalls', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('availableIncludes', $validatedMetaData['resourceInfo']);
        $this->assertEquals(5, count($validatedMetaData['resourceInfo'])); // test fails if not up dated
    }

    public function requestMethodProvider() : array
    {
        return [
            'GET' => ['GET'],
            'POST' => ['POST'],
            'PUT' => ['PUT'],
            'PATCH' => ['PATCH'],
            'DELETE' => ['DELETE'],
        ];
    }

    protected function getRestRequestValidatorResponse(array $parameters = [], $url = 'api/v1/projects', $method = 'GET') : array
    {
        $request = Request::create($url, $method, $parameters);
        $restRequestDataPrepper = new RestRequestDataPrepper($request);
        $validatorDataCollector = App::make(ValidatorDataCollector::class);
        $endpointValidator = App::make(EndpointValidator::class);
        $requestMethodTypeValidatorFactory = App::make(RequestMethodTypeValidatorFactory::class);

        $restRequestValidator = new RestRequestValidator($restRequestDataPrepper, $validatorDataCollector, $endpointValidator, $requestMethodTypeValidatorFactory);

        return $restRequestValidator->validate();
    }
}
