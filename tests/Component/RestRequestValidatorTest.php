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
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_RestRequestValidator_returns_expected_result_array_with_correct_keys($requestMethod)
    {
        $validatedMetaData = $this->getRestRequestValidatorResponse([
            'resource' => 'projects',
            'id' => 33,
            'title' => 'Test Project',
        ], 'api/v1/projects', $requestMethod);
        
        // * asserting structure, details tested in other classes like ResourceModelInfoProvider, ResourceParameterInfoProviders, etc.
        $this->assertArrayHasKey('endpointData', $validatedMetaData);
        $this->assertArrayHasKey('resourceInfo', $validatedMetaData);
        $this->assertArrayHasKey('acceptableParameters', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('availableMethodCalls', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('availableIncludes', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('rejectedParameters', $validatedMetaData);
        $this->assertArrayHasKey('acceptedParameters', $validatedMetaData);
        $this->assertArrayHasKey('queryArguments', $validatedMetaData);
    }

    public function requestMethodProvider()
    {
        return [
            'GET' => ['GET'],
            'POST' => ['POST'],
            'PUT' => ['PUT'],
            'PATCH' => ['PATCH'],
            'DELETE' => ['DELETE'],
        ];
    }

    protected function getRestRequestValidatorResponse(array $parameters = [], $url = 'api/v1/projects', $method = 'GET')
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
