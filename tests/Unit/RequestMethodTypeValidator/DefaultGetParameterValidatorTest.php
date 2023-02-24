<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\DefaultGetParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

// ! Start here ******************************************************************
// ! read over file and test readability, test coverage, test organization, tests grouping, go one by one
// ! (sub ParameterValidatorFactory, PostRequestMethodTypeValidator)
// [x] read over
// [x] add return type : void
// [] add test
// test to do
// [] read over
// [] test groups, rest, contet
// [] add return type : void
// [] testing what I need to test

// TODO: check to make sure I got all the HttpResponseException
class DefaultGetParameterValidatorTest extends TestCase
{
    protected $validatorDataCollector;
    protected $defaultGetParameterValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = App::make(ValidatorDataCollector::class);
        $this->defaultGetParameterValidator = App::make(DefaultGetParameterValidator::class);
    }

    /**
     * @dataProvider singleValueParameterProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_AcceptedParameters_are_set_correctly_single_value($parameterName, $parameterValue, $acceptedColumnName): void
    {
        $this->defaultGetParameterValidator->validate($parameterName, $parameterValue, $this->validatorDataCollector);

        $this->assertEquals($parameterValue, $this->validatorDataCollector->getAcceptedParameters()[$acceptedColumnName]);
    }

    public function singleValueParameterProvider(): array
    {
        return [
            'page' => ['page', 10, 'page'],
            'perpage' => ['perpage', 50, 'perPage'],
            'per_page' => ['per_page', 50, 'perPage'],
        ];
    }

    /**
     * @dataProvider invalidParameterProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_RejectedParameters_are_set_correctly($parameterName, $acceptedColumnName, $expectedResult): void
    {
        $this->defaultGetParameterValidator->validate($parameterName, $expectedResult['value'], $this->validatorDataCollector);

        $this->assertEquals($expectedResult, $this->validatorDataCollector->getRejectedParameters()[$acceptedColumnName]);
    }

    public function invalidParameterProvider(): array
    {
        $expectedResult = [
            'value' => 'not a number',
            'parameterError' => 'This parameter\'s value must be an int.'
        ];

        return [
            'page' => [
                'page',
                'page',
                $expectedResult,
            ],
            'perpage' => [
                'perpage',
                'perPage',
                $expectedResult,
            ],
            'per_page' => [
                'per_page',
                'perPage',
                $expectedResult,
            ],
        ];
    }

    /**
     * @dataProvider valueFieldParameterProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_AcceptedParameters_are_set_correctly_value_field($parameterName, $acceptedColumnName, $expectedResult): void
    {
        $this->defaultGetParameterValidator->validate($parameterName, $expectedResult['value'], $this->validatorDataCollector);

        $this->assertEquals($expectedResult, $this->validatorDataCollector->getAcceptedParameters()[$acceptedColumnName]);
    }

    public function valueFieldParameterProvider(): array
    {
        $columnData = [
            'value' => 'yes',
            'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter data for this resource/endpoint'
        ];

        $formData = [
            'value' => 'yes',
            'message' => 'This parameter\'s value dose not matter. If this parameter is set it well high jack the request and only return parameter form data for this resource/endpoint'
        ];

        return [
            'columndata' => [
                'columndata',
                'columnData',
                $columnData,
            ],
            'column_data' => [
                'column_data',
                'columnData',
                $columnData,
            ],
            'formdata' => [
                'formdata',
                'formData',
                $formData,
            ],
            'form_data' => [
                'form_data',
                'formData',
                $formData,
            ],
        ];
    }
}
