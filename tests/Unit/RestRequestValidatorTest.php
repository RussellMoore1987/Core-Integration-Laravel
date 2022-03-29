<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\DataTypeDeterminerFactory;
use App\CoreIntegrationApi\ParameterValidatorFactory\ParameterValidatorFactory;
use App\CoreIntegrationApi\RestApi\RestRequestDataPrepper;
use App\CoreIntegrationApi\RestApi\RestRequestValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class RestRequestValidatorTest extends TestCase
{
    // TODO: Test
    // data collection, what it validates***, all data returned, endpoint, endpointId, column validation, default parameters, other
    // other validation done else were***
    // endpointData
    // rejectedParameters
    // acceptedParameters

    // TODO: testing url***
    // TODO: dynamic index ex: api/v1, api/v2, api/rest/v1, api/context/v3
    // TODO: 

    // TODO: Test in other class
    // classDataProvider, also add it to RequestValidator
      // acceptableParameters
      // availableMethodCalls
      // availableIncludes

    public function test_context_request_data_prepper_returns_expected_result()
    {
        $validatedMetaData = $this->validateRequest([
            'endpoint' => 'projects',
            'start_date' => '2020-02-28',
            'id' => 33,
            'title' => 'Gogo!!!'
        ]);

        dd($_SERVER,$validatedMetaData);

        $this->assertArrayHasKey('endpointData');
        $this->assertArrayHasKey('extraData');
        $this->assertArrayHasKey('rejectedParameters');
        $this->assertArrayHasKey('acceptedParameters');
        $this->assertArrayHasKey('queryArguments');

      //   "acceptedParameters" => array:3 [
      //     "endpoint" => array:1 [
      //       "messsage" => ""projects" is a valid endpoint for this API. You can also 
      // review available endpoints at http://"
      //     ]

      //     "endpointData" => array:8 

        


        $expectedEndpointData = [
          'endpoint' => 'projects',
          'endpointId' => 33,
          'endpointError' => false,
          'class' => 'App\Models\Project',
          'indexUrl' => 'http://',
          'url' => 'http://localhost:8000',
          'httpMethod' => 'GET',
          'endpointIdConvertedTo' => [
            'id' => 33
          ]
        ];

        $this->assertEquals($expectedEndpointData,$validatedMetaData);
    }

    

    protected function validateRequest(array $parameters = [], $url = 'api/v1/projects', $method = 'GET')
    {
        $request = Request::create($url, $method, $parameters);
        $restRequestDataPrepper = new RestRequestDataPrepper($request);

        $dataTypeDeterminerFactory = App::make(DataTypeDeterminerFactory::class);
        $parameterValidatorFactory = App::make(ParameterValidatorFactory::class);
        $validatorDataCollector = App::make(ValidatorDataCollector::class);


        $restRequestValidator = new RestRequestValidator($restRequestDataPrepper, $dataTypeDeterminerFactory, $parameterValidatorFactory, $validatorDataCollector);

        return $restRequestValidator->validate();
    }
}
