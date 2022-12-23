<?php

namespace Tests\Component;

use App\CoreIntegrationApi\EndpointValidator;
use App\Models\Project;
use Tests\TestCase;
use Illuminate\Support\Facades\App;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;

class EndpointValidatorTest extends TestCase
{
    protected $validatorDataCollector;
    protected $endpointValidator;
    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = new ValidatorDataCollector();
        $this->validatorDataCollector->resource = 'projects';
        $this->validatorDataCollector->resourceId = '33';
        $this->validatorDataCollector->parameters = ['title' => 'Test Project'];
        $this->validatorDataCollector->requestMethod = 'GET';
        $this->validatorDataCollector->url = 'http://localhost/api/v1/projects';

        $this->endpointValidator = App::make(EndpointValidator::class);
    }

    /**
     * @dataProvider returnsExpectedResultProvider
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_EndpointValidator_returns_expected_result_for_setting_request_methods($requestMethod)
    {
        $this->validatorDataCollector->requestMethod = $requestMethod;
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $expectedEndpointData = [
            'resource' => 'projects',
            'resourceId' => '33',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => $requestMethod,
            'resourceIdConvertedTo' => [
                'id' => 33
            ]
        ];

        $this->assertEquals($expectedEndpointData, $this->validatorDataCollector->endpointData);
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

    /**
     * Testing GET, but path applies to all
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_EndpointValidator_returns_expected_result_for_endpointData()
    {
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $expectedEndpointData = [
            'resource' => 'projects',
            'resourceId' => '33',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => 'GET',
            'resourceIdConvertedTo' => [
                'id' => 33
            ]
        ];

        $expectedEndpointAcceptedParameters = [
                'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost/api/v1/'
        ];


        $this->assertEquals($expectedEndpointData, $this->validatorDataCollector->endpointData);
        $this->assertEquals($expectedEndpointAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters()['endpoint']);
    }

    /**
     * Testing GET, but path applies to all
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_EndpointValidator_returns_expected_result_for_endpointData_no_id()
    {
        $this->validatorDataCollector->resourceId = '';
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $expectedEndpointData = [
            'resource' => 'projects',
            'resourceId' => '',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => 'GET',
        ];

        $this->assertEquals($expectedEndpointData, $this->validatorDataCollector->endpointData);
    }


    /**
     * Testing GET, but path applies to all
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_EndpointValidator_returns_expected_resource_info()
    {
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $this->assertInstanceOf(Project::class, $this->validatorDataCollector->resourceObject);
        // just asserting structure, details tested in ResourceModelInfoProvider and ResourceParameterInfoProviders
        $this->assertTrue((boolean) $this->validatorDataCollector->resourceInfo);
        $this->assertArrayHasKey('acceptableParameters', $this->validatorDataCollector->resourceInfo);
        $this->assertArrayHasKey('availableMethodCalls', $this->validatorDataCollector->resourceInfo);
        $this->assertArrayHasKey('availableIncludes', $this->validatorDataCollector->resourceInfo);
    }

    /**
     * Testing GET, but path applies to all
     * @group get
     * @group post
     * @group put
     * @group patch
     * @group delete
     */
    public function test_EndpointValidator_returns_HttpResponseException_when_no_resource_is_provided()
    {
        // exception details tested in FullRestApiTest
        $this->expectException(HttpResponseException::class);

        $this->validatorDataCollector->resource = '';
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);
    }
}
