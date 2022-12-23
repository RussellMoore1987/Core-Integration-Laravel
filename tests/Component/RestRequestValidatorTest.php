<?php

namespace Tests\Component;

use App\CoreIntegrationApi\EndpointValidator;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidatorFactory;
use App\CoreIntegrationApi\RestApi\RestRequestValidator;
use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;

class RestRequestValidatorTest extends TestCase
{
    // TODO: refactor this ???
    /**
     * @dataProvider returnsExpectedResultProvider
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_RestRequestValidator_returns_expected_result($requestMethod)
    {
        $validatedMetaData = $this->validateRequest([
            'resource' => 'projects',
            'id' => 33,
            'title' => 'Test Project',
        ], 'api/v1/projects', $requestMethod);

        // just asserting structure, details tested in ResourceModelInfoProvider and ResourceParameterInfoProviders
        $this->assertArrayHasKey('endpointData', $validatedMetaData);
        $this->assertArrayHasKey('resourceInfo', $validatedMetaData);
        $this->assertArrayHasKey('acceptableParameters', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('availableMethodCalls', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('availableIncludes', $validatedMetaData['resourceInfo']);
        $this->assertArrayHasKey('rejectedParameters', $validatedMetaData);
        $this->assertArrayHasKey('acceptedParameters', $validatedMetaData);
        $this->assertArrayHasKey('queryArguments', $validatedMetaData);
    }

    public function returnsExpectedResultProvider()
    {
        return [
            'GET' => ['GET'],
            'POST' => ['POST'],
            'PUT' => ['PUT'],
            'PATCH' => ['PATCH'],
            'DELETE' => ['DELETE'],
        ];
    }

    protected function validateRequest(array $parameters = [], $url = 'api/v1/projects', $method = 'GET')
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
