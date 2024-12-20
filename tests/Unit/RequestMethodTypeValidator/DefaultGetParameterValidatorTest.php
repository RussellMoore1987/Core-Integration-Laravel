<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\DefaultGetParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

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
     * @dataProvider pageAndPerpageParameterProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_AcceptedParameters_are_set_correctly_page_and_perpage($parameterName, $parameterValue, $acceptedColumnName): void
    {
        $this->defaultGetParameterValidator->validate($parameterName, $parameterValue, $this->validatorDataCollector);

        $this->assertEquals($parameterValue, $this->validatorDataCollector->getAcceptedParameters()[$acceptedColumnName]);
    }

    public function pageAndPerpageParameterProvider(): array
    {
        return [
            'page' => ['page', 10, 'page'],
            'perpage' => ['perpage', 50, 'perPage'],
            'per_page' => ['per_page', 50, 'perPage'],
        ];
    }

    /**
     * @dataProvider invalidPageAndPerpageParameterProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_RejectedParameters_are_set_correctly_page_and_perpage($parameterName, $acceptedColumnName, $expectedResult): void
    {
        $this->defaultGetParameterValidator->validate($parameterName, $expectedResult['value'], $this->validatorDataCollector);

        $this->assertEquals($expectedResult, $this->validatorDataCollector->getRejectedParameters()[$acceptedColumnName]);
    }

    public function invalidPageAndPerpageParameterProvider(): array
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
     * @dataProvider columnDataAndFormDataParameterProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_AcceptedParameters_are_set_correctly_columnData_and_formData($parameterName, $acceptedColumnName, $expectedResult): void
    {
        $this->defaultGetParameterValidator->validate($parameterName, $expectedResult['value'], $this->validatorDataCollector);

        $this->assertEquals($expectedResult, $this->validatorDataCollector->getAcceptedParameters()[$acceptedColumnName]);
    }

    public function columnDataAndFormDataParameterProvider(): array
    {
        $columnData = [
            'value' => 'yes',
            'message' => 'This parameter\'s value dose not matter. If this parameter is set it will high jack the request and only return parameter data for this resource/endpoint'
        ];

        $formData = [
            'value' => 'yes',
            'message' => 'This parameter\'s value dose not matter. If this parameter is set it will high jack the request and only return parameter form data for this resource/endpoint'
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
