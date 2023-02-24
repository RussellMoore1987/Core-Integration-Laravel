<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\EndpointValidator;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\GetRequestMethodTypeValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

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

    /**
     * @group rest
     * @group context
     * @group get
     */
    // details tested in tests\Integration\HttpResponseExceptionRequestTest.php
    public function test_GetRequestMethodTypeValidator_throws_exception_when_parameters_are_rejected(): void
    {
        $this->expectException(HttpResponseException::class);
        
        $this->setUpValidatorDataCollector(['notValid' => '1234',]);

        $this->getRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_GetRequestMethodTypeValidator_sets_defaultResourceParameters_has_data(): void
    {
        $this->setUpValidatorDataCollector(['columns' => 'title']);

        $this->getRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);

        $this->assertEquals(2, count($this->validatorDataCollector->getAcceptedParameters()));
        $this->assertEquals(0, count($this->validatorDataCollector->getRejectedParameters()));
        $this->assertEquals(1, count($this->validatorDataCollector->getQueryArguments()));
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_GetRequestMethodTypeValidator_sets_acceptableParameters_has_data(): void
    {
        $this->setUpValidatorDataCollector([
            'id' => 1234,
            'start_date' => '2022-10-23',
            'is_published' => 1,
        ]);

        $this->getRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);

        $this->assertEquals(4, count($this->validatorDataCollector->getAcceptedParameters()));
        $this->assertEquals(0, count($this->validatorDataCollector->getRejectedParameters()));
        $this->assertEquals(3, count($this->validatorDataCollector->getQueryArguments()));
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_GetRequestMethodTypeValidator_sets_defaultGetParameters_has_data(): void
    {
        $this->setUpValidatorDataCollector([
            'page' => 12,
            'perPage' => 50,
        ]);

        $this->getRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);

        $this->assertEquals(3, count($this->validatorDataCollector->getAcceptedParameters()));
        $this->assertEquals(0, count($this->validatorDataCollector->getRejectedParameters()));
        $this->assertEquals(0, count($this->validatorDataCollector->getQueryArguments()));
    }

    /**
     * @group rest
     * @group context
     * @group get
     */
    public function test_GetRequestMethodTypeValidator_sets_empty_arrays_for_all_parameter_types(): void
    {
        $this->setUpValidatorDataCollector();

        $this->getRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);

        $this->assertEquals(1, count($this->validatorDataCollector->getAcceptedParameters()));
        $this->assertEquals(0, count($this->validatorDataCollector->getRejectedParameters()));
        $this->assertEquals(0, count($this->validatorDataCollector->getQueryArguments()));
    }

    protected function setUpValidatorDataCollector(array $parameters = []): void
    {
        $this->validatorDataCollector = App::make(ValidatorDataCollector::class);
        $this->validatorDataCollector->resource = 'projects';
        $this->validatorDataCollector->parameters = $parameters;

        $endpointValidator = App::make(EndpointValidator::class);
        $endpointValidator->validateEndPoint($this->validatorDataCollector);
    }
}
