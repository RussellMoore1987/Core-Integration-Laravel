<?php

namespace Tests\Unit\RequestMethodTypeValidator;

use App\CoreIntegrationApi\ResourceDataProvider;
use App\CoreIntegrationApi\RequestMethodTypeValidatorFactory\RequestMethodTypeValidators\PostRequestMethodTypeValidator;
use App\CoreIntegrationApi\ParameterDataProviderFactory\ParameterDataProviderFactory;
use App\CoreIntegrationApi\ValidatorDataCollector;
use App\Models\Category;
use App\Models\WorkHistoryType;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tests\TestCase;

class PostRequestMethodTypeValidatorTest extends TestCase
{
    // End-to-end done in tests\Feature\PostRequestMethodTest.php

    protected function setUp(): void
    {
        parent::setUp();

        $this->postRequestMethodTypeValidator = new PostRequestMethodTypeValidator();
        $this->validatorDataCollector = new ValidatorDataCollector();
    }

    public function test_PostRequestMethodTypeValidator_returns_correct_data()
    {
        $this->setRequestData($this->setUpWorkHistoryTypeClass());
        $name = 'Test WorkHistoryType';
        $icon = 'fa-user';
        $this->validatorDataCollector->parameters = [
            'name' => $name,
            'icon' => $icon,
        ];
        
        $expectedResults = [
            'rejectedParameters' => [],
            'acceptedParameters' => [
              'name' => $name,
              'icon' => $icon,
            ],
            'queryArguments' => [
              'name' => $name,
              'icon' => $icon,
            ]
        ];

        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);
        $actualResults = $this->validatorDataCollector->getValidatedMetaData();

        // just testing the ones that are set in the class
        $this->assertEquals($expectedResults['rejectedParameters'], $actualResults['rejectedParameters']);
        $this->assertEquals($expectedResults['acceptedParameters'], $actualResults['acceptedParameters']);
        $this->assertEquals($expectedResults['queryArguments'], $actualResults['queryArguments']);
    }

    /**
     * @dataProvider parameterToValidateProvider
     */
    public function test_PostRequestMethodTypeValidator_throws_exception_when_model_getValidationRules_criteria_not_met($parameters)
    {
        $this->setRequestData($this->setUpWorkHistoryTypeClass());
        $this->validatorDataCollector->parameters = $parameters;

        $this->expectException(HttpResponseException::class);

        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);
    }

    protected function setUpWorkHistoryTypeClass()
    {
        $workHistoryType = new WorkHistoryType();
        $workHistoryType->validationRules = [
            'modelValidation' => [
                'work_history_type_id' => [
                    'integer',
                    'min:1',
                    'max:18446744073709551615',
                ],
                'name' => [
                    'string',
                    'max:35',
                    'min:2',
                ],
                'icon' => [
                    'string',
                    'max:50',
                    'min:2',
                ],
            ],
            'createValidation' => [
                'name' => [
                    'required',
                ],
            ],
        ];

        return $workHistoryType;
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
        $this->setRequestData(new Category());
        $name = 'Web Development';
        $this->validatorDataCollector->parameters = ['name' => $name]; // Category only require name

        $expectedResults = [
            'rejectedParameters' => [],
            'acceptedParameters' => [
                'name' => $name,
            ],
            'queryArguments' => [
                'name' => $name,
            ]
        ];
        
        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);
        $actualResults = $this->validatorDataCollector->getValidatedMetaData();

        // just testing the ones that are set in the class
        $this->assertEquals($expectedResults['rejectedParameters'], $actualResults['rejectedParameters']);
        $this->assertEquals($expectedResults['acceptedParameters'], $actualResults['acceptedParameters']);
        $this->assertEquals($expectedResults['queryArguments'], $actualResults['queryArguments']);
    }

    public function test_PostRequestMethodTypeValidator_returns_correct_data_in_regards_to_setRejectedParameters()
    {
        $this->setRequestData(new Category());
        $name = 'Web Development';
        $this->validatorDataCollector->parameters = [
            'name' => $name,
            'NotGoodParameter' => 'Yep! Not good!',
            'so_not_good' => 12345,
        ];

        $expectedResults = [
            'rejectedParameters' => [
                'NotGoodParameter' => 'Yep! Not good!',
                'so_not_good' => 12345,
            ],
            'acceptedParameters' => [
                'name' => $name,
            ],
            'queryArguments' => [
                'name' => $name,
            ]
        ];
        
        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);
        $actualResults = $this->validatorDataCollector->getValidatedMetaData();

        // just testing the ones that are set in the class
        $this->assertEquals($expectedResults['rejectedParameters'], $actualResults['rejectedParameters']);
        $this->assertEquals($expectedResults['acceptedParameters'], $actualResults['acceptedParameters']);
        $this->assertEquals($expectedResults['queryArguments'], $actualResults['queryArguments']);
    }

    public function test_PostRequestMethodTypeValidator_throws_exception_when_model_defaultValidationRules_criteria_not_met()
    {
        $this->setRequestData(new Category());
        $this->validatorDataCollector->parameters = ['name' => '']; // Category class Requires a valid name

        $this->expectException(HttpResponseException::class);

        $this->postRequestMethodTypeValidator->validateRequest($this->validatorDataCollector);
    }

    protected function setRequestData(object $class)
    {
        // TODO: rename things to make more sens
        // TODO: make it so I can mercilessly refactor it
        $resourceDataProvider = new ResourceDataProvider(new ParameterDataProviderFactory());
        $resourceDataProvider->setResource($class);
        $resourceInfo = $resourceDataProvider->getResourceInfo();

        $this->resourceInfo = $resourceInfo;

        $this->validatorDataCollector->resourceInfo = $this->resourceInfo;
        $this->validatorDataCollector->resourceObject = $class;
    }
}
