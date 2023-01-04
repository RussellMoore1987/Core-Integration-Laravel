<?php

namespace Tests\Component;

use App\CoreIntegrationApi\EndpointValidator;
use App\Models\Project;
use Tests\TestCase;
use Illuminate\Support\Facades\App;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;

// ! Start here ****************************************************************** read over file and test readability, test coverage, test organization, tests grouping, go one by one (I have a stash of tests**** EndpointValidatorTest.php)
// [x] read over
// [] test groups, rest, context
// [x] add return type : void
// [] testing what I need to test

class EndpointValidatorTest extends TestCase
{
    protected $validatorDataCollector;
    protected $endpointValidator;
    protected $expectedEndpointData;

    protected function setUp() : void
    {
        parent::setUp();

        $this->validatorDataCollector = new ValidatorDataCollector();
        $this->validatorDataCollector->resource = 'projects';
        $this->validatorDataCollector->resourceId = '33';
        $this->validatorDataCollector->parameters = ['title' => 'Test Project'];
        $this->validatorDataCollector->requestMethod = 'GET';
        $this->validatorDataCollector->url = 'http://localhost/api/v1/projects';

        $this->expectedEndpointData = [
            'resource' => 'projects',
            'resourceId' => '33',
            'indexUrl' => 'http://localhost/api/v1/',
            'url' => 'http://localhost/api/v1/projects',
            'requestMethod' => 'GET',
            'resourceIdConvertedTo' => [
                'id' => 33
            ]
        ];

        $this->endpointValidator = App::make(EndpointValidator::class);
    }

    /**
     * @dataProvider requestMethodsProvider
     * @group allRequestMethods
     */
    public function test_EndpointValidator_returns_expected_result_for_setting_request_methods($requestMethod) : void
    {
        $this->validatorDataCollector->requestMethod = $requestMethod;
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $this->expectedEndpointData['requestMethod'] = $requestMethod;

        $this->assertEquals($this->expectedEndpointData, $this->validatorDataCollector->endpointData);
    }

    public function requestMethodsProvider() : array
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
     * @group allRequestMethods
     */
    public function test_EndpointValidator_returns_expected_result_for_getAcceptedParameters_endpoint() : void
    {
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $expectedEndpointAcceptedParameters = [
                'message' => '"projects" is a valid resource/endpoint for this API. You can also review available resources/endpoints at http://localhost/api/v1/'
        ];

        $this->assertEquals($expectedEndpointAcceptedParameters, $this->validatorDataCollector->getAcceptedParameters()['endpoint']);
    }

    /**
     * Testing GET, but path applies to all
     * @group allRequestMethods
     */
    public function test_EndpointValidator_returns_expected_result_for_endpointData_no_id() : void
    {
        $this->validatorDataCollector->resourceId = '';
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $this->expectedEndpointData['resourceId'] = '';
        unset($this->expectedEndpointData['resourceIdConvertedTo']);

        $this->assertEquals($this->expectedEndpointData, $this->validatorDataCollector->endpointData);
    }

    /**
     * Testing GET, but path applies to all
     * @group allRequestMethods
     */
    public function test_EndpointValidator_returns_expected_resourceInfo() : void
    {
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $this->assertInstanceOf(Project::class, $this->validatorDataCollector->resourceObject);
        // just asserting structure, details tested in ResourceModelInfoProvider and ResourceParameterInfoProviders
        $this->assertTrue((boolean) $this->validatorDataCollector->resourceInfo);
        $this->assertArrayHasKey('primaryKeyName', $this->validatorDataCollector->resourceInfo);
        $this->assertArrayHasKey('path', $this->validatorDataCollector->resourceInfo);
        $this->assertArrayHasKey('acceptableParameters', $this->validatorDataCollector->resourceInfo);
        $this->assertArrayHasKey('availableMethodCalls', $this->validatorDataCollector->resourceInfo);
        $this->assertArrayHasKey('availableIncludes', $this->validatorDataCollector->resourceInfo);
        $this->assertEquals(5, count($this->validatorDataCollector->resourceInfo)); // test fails if not up dated
    }

    /**
     * Testing GET, but path applies to all
     * @dataProvider notValidResourceProvider
     * @group allRequestMethods
     */
    public function test_EndpointValidator_returns_HttpResponseException_when_resource_is_invalid($invalidResource) : void
    {
        // exception details tested in FullRestApiTest
        $this->expectException(HttpResponseException::class);

        $this->validatorDataCollector->resource = $invalidResource;
        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);
    }

    public function notValidResourceProvider() : array
    {
        return [
            'blankString' => [''],
            'notAnAvailableResourceEndpoint' => ['notAnEndpoint'],
        ];
    }

    /**
     * Testing GET, but path applies to all
     * @group allRequestMethods
     */
    public function test_EndpointValidator_returns_expected_result_for_the_index_endpoint() : void
    {
        $this->validatorDataCollector->resourceId = '';
        $this->validatorDataCollector->resource = 'index';
        $this->validatorDataCollector->parameters = [];
        $this->validatorDataCollector->requestMethod = 'GET';
        $this->validatorDataCollector->url = 'http://localhost/api/v1';

        $this->endpointValidator->validateEndPoint($this->validatorDataCollector);

        $this->assertEquals([], $this->validatorDataCollector->endpointData);
        $this->assertFalse((boolean) $this->validatorDataCollector->resourceInfo);
        $this->assertEquals([], $this->validatorDataCollector->getAcceptedParameters());
    }
}
