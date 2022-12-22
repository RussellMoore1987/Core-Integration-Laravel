<?php

namespace Tests\Unit;

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
    /**
     * @dataProvider returnsExpectedResultProvider
     */
    public function test_RestRequestValidator_returns_expected_result($requestMethod)
    {
        $validatedMetaData = $this->validateRequest([
            'resource' => 'projects',
            'id' => 33,
            'title' => 'Test Project',
        ], 'api/v1/projects', $requestMethod);

        // just asserting structure, details tested else were
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
