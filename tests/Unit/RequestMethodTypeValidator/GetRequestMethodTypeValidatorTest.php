<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\GetRequestMethodTypeValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (sub ParameterValidatorFactory (change if statements to api data types), PostRequestMethodTypeValidator.php)
// [] read over
// [] add return type : void
// [] add test
// test to do
// [] read over
// [] test groups, rest, context
// [] add return type : void
// [] testing what I need to test

// TODO: check to make sure I got all the HttpResponseException
class GetRequestMethodTypeValidatorTest extends TestCase
{
    protected $validatorDataCollector;
    protected $getRequestMethodTypeValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getRequestMethodTypeValidator = App::make(GetRequestMethodTypeValidator::class);
        
    }

    // details tested in tests\Integration\HttpResponseExceptionRequestTest.php
    public function test_GetRequestMethodTypeValidator_throws_exception_when_parameters_are_rejected(): void
    {
        $this->expectException(HttpResponseException::class);
        
        $this->setUpValidatorDataCollector(['notValid' => '1234',]);

        $this->getRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);
    }

    public function test_data()
    {
        $this->setUpValidatorDataCollector();

        $this->getRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);
        // dd($this->validatorDataCollector);
    }

    public function setUpValidatorDataCollector(array $parameters = []): void
    {
        $this->validatorDataCollector = App::make(ValidatorDataCollector::class);
        $this->validatorDataCollector->resource = 'projects';
        $this->validatorDataCollector->parameters = $parameters;

        $endpointValidator = App::make(EndpointValidator::class);
        $endpointValidator->validateEndPoint($this->validatorDataCollector);
    }

    // ?? [
    //     'id' => 1234,
    //     'title' => 'So Cool!',
    //     'roles' => 'designer'
    // ]
}
