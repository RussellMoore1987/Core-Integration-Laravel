<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\IndexParameterValidator;
use App\CoreIntegrationApi\ValidatorDataCollector;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class IndexParameterValidatorTest extends TestCase
{
    protected $validatorDataCollector;
    protected $indexParameterValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorDataCollector = App::make(ValidatorDataCollector::class);
        $this->indexParameterValidator = App::make(IndexParameterValidator::class);
    }

    /**
     * @dataProvider indexParameterProvider
     * @group rest
     * @group context
     * @group get
     */
    public function test_IndexParameterValidator_sets_parameters_correctly($parameterName, $parameterValue, $acceptedColumnName, $expectedResult): void
    {
        $this->indexParameterValidator->validate($parameterName, $parameterValue, $this->validatorDataCollector);

        $this->assertEquals($expectedResult, $this->validatorDataCollector->getAcceptedParameters()[$acceptedColumnName]);
    }

    public function indexParameterProvider(): array
    {
        $aboutResult = [
            'value' => 'yes',
            'message' => 'This parameter\'s value dose not matter. If set it will return the about information for the API index.'
        ];

        $generalDocResult = [
            'value' => 'yes',
            'message' => 'This parameter\'s value dose not matter. If set it will return the general documentation information for the API index.'
        ];

        $quickRouteResult = [
            'value' => 'yes',
            'message' => 'This parameter\'s value dose not matter. If set it will return the quick route reference information for the API index.'
        ];

        $routesResult = [
            'value' => 'yes',
            'message' => 'This parameter\'s value dose not matter. If set it will return the routes information for the API index.'
        ];

        return [
            'about' => ['about', 'yes', 'about', $aboutResult],
            'generaldocumentation' => ['generaldocumentation', 'yes', 'generalDocumentation', $generalDocResult],
            'general_documentation' => ['general_documentation', 'yes', 'generalDocumentation', $generalDocResult],
            'quickroutereference' => ['quickroutereference', 'yes', 'quickRouteReference', $quickRouteResult],
            'quick_route_reference' => ['quick_route_reference', 'yes', 'quickRouteReference', $quickRouteResult],
            'routes' => ['routes', 'yes', 'routes', $routesResult],
        ];
    }
}
