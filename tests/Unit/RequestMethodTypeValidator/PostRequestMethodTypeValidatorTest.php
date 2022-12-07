<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\ResourceDataProvider;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\PostRequestMethodTypeValidator;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviderFactory;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\Models\Category;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\WorkHistoryType;
use Tests\TestCase;

class PostRequestMethodTypeValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->postRequestMethodTypeValidator = new PostRequestMethodTypeValidator();
        $this->validatorDataCollector = new ValidatorDataCollector();
    }

    public function test_PostRequestMethodTypeValidator_returns_correct_data()
    {
        $requestData = $this->setRequestData(new WorkHistoryType());

        $expectedResult = [
            'endpointData' => [],
            'resourceInfo' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [
                'name' => 'Test WorkHistoryType',
                'icon' => 'fa-user',
            ],
            'queryArguments' => [
                'name' => 'Test WorkHistoryType',
                'icon' => 'fa-user',
            ],
        ];

        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);

        $this->assertEquals($expectedResult, $this->validatorDataCollector->getAllData());
    }

    /**
     * @dataProvider parameterToValidateProvider
     */
    public function test_PostRequestMethodTypeValidator_throws_exception_when_model_getValidationRules_criteria_not_met($parameters)
    {
        $requestData = $this->setRequestData(new WorkHistoryType());
        $requestData['parameters'] = $parameters; // WorkHistoryType class parameter will throw an error

        $this->expectException(HttpResponseException::class);

        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);
    }

    public function parameterToValidateProvider()
    {
        return [
            'less then 2 charters' => [['name' => 'r']],
            'more then 35 charters' => [['name' => 'more then 35 charters 22we3er4t5redsd']],
            'required' => [['name' => '']],
        ];
    }

    public function test_PostRequestMethodTypeValidator_returns_correct_data_for_defaultValidationRules()
    {
        $requestData = $this->setRequestData(new Category());

        $expectedResult = [
            'endpointData' => [],
            'resourceInfo' => [],
            'rejectedParameters' => [],
            'acceptedParameters' => [
                'name' => 'Web Development',
            ],
            'queryArguments' => [
                'name' => 'Web Development',
            ],
        ];

        $requestData['parameters'] = ['name' => 'Web Development']; // Category only require name

        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);

        $this->assertEquals($expectedResult, $this->validatorDataCollector->getAllData());
    }

    public function test_PostRequestMethodTypeValidator_returns_correct_data_in_regards_to_setRejectedParameter()
    {
        $requestData = $this->setRequestData(new Category());

        $expectedResult = [
            'endpointData' => [],
            'resourceInfo' => [],
            'rejectedParameters' => [
                'NotGoodParameter' => 'Yep! Not good!',
                'so_not_good' => 12345,
            ],
            'acceptedParameters' => [
                'name' => 'Web Development',
            ],
            'queryArguments' => [
                'name' => 'Web Development',
            ],
        ];

        $requestData['parameters'] = [
            'name' => 'Web Development',
            'NotGoodParameter' => 'Yep! Not good!',
            'so_not_good' => 12345,
        ];

        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);

        $this->assertEquals($expectedResult, $this->validatorDataCollector->getAllData());
    }

    public function test_PostRequestMethodTypeValidator_throws_exception_when_model_defaultValidationRules_criteria_not_met()
    {
        $requestData = $this->setRequestData(new Category());
        $requestData['parameters'] = ['name' => '']; // Category class Requires a valid name

        $this->expectException(HttpResponseException::class);

        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector, $requestData);
    }

    protected function setRequestData(object $class)
    {
        // TODO: rename things to make more sens
        // TODO: make it so I can mercilessly refactor it
        $resourceDataProvider = new ResourceDataProvider(new ParameterDataProviderFactory());
        $resourceDataProvider->setResource($class);
        $resourceInfo = $resourceDataProvider->getResourceInfo();

        $this->resourceInfo = $resourceInfo;

        return [
            'parameters' => [
                'name' => 'Test WorkHistoryType',
                'icon' => 'fa-user',
            ],
            'resourceInfo' => $this->resourceInfo,
            'resourceObject' => $class,
        ];
    }
}
