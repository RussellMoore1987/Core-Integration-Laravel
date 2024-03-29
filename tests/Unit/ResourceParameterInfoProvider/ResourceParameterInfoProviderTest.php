<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\Exceptions\ResourceParameterInfoProviderException;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\IntResourceParameterInfoProvider;
use Tests\TestCase;

class ResourceParameterInfoProviderTest extends TestCase
{
    protected $parameterAttributeArray = [
        'field' => 'fakeParameterName',
        'type' => 'tinyint',
        'null' => 'yes',
        'key' => '',
        'default' => null,
        'extra' => '',
    ];

    /**
     * @dataProvider attributeExceptionProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_TestResourceParameterInfoProvider_throws_exception_when_required_attributes_are_not_set(string $classAttribute, int $code, $attributeValue): void
    {
        $this->expectException(ResourceParameterInfoProviderException::class);
        $this->expectErrorMessage("The class attribute {$classAttribute} must be set in the child class \"Tests\Unit\ResourceParameterInfoProvider\TestResourceParameterInfoProvider\".");
        $this->expectExceptionCode($code);

        $testResourceParameterInfoProvider = new TestResourceParameterInfoProvider();
        $testResourceParameterInfoProvider->$classAttribute = $attributeValue;

        $testResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);
    }
    public function attributeExceptionProvider(): array
    {
        return [
            'apiDataTypeException' => [
                'apiDataType',
                100,
                null
            ],
            'defaultValidationRulesException' => [
                'defaultValidationRules',
                101,
                []
            ],
            'formDataException' => [
                'formData',
                102,
                []
            ],
        ];
    }

    /**
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_ResourceParameterInfoProvider_isParameterRequired_sets_data_correctly(): void
    {
        $intResourceParameterInfoProvider = new IntResourceParameterInfoProvider();
        $this->parameterAttributeArray['null'] = 'no';

        $result = $intResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);
        
        $this->assertEquals(true, $result['formData']['required']);
        $this->assertTrue(in_array('required', $result['defaultValidationRules']));
    }

    /**
     * @dataProvider formDataProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_ResourceParameterInfoProvider_mergeResourceParameterFormData_sets_data_correctly(array $formData, array $expectedFormData): void
    {
        $intResourceParameterInfoProvider = new IntResourceParameterInfoProvider();
        $formData = [
            'fakeParameterName' => $formData,
        ];

        $result = $intResourceParameterInfoProvider->getData($this->parameterAttributeArray, $formData);

        $this->assertEquals($expectedFormData, $result['formData']);
    }

    public function formDataProvider(): array
    {
        return [
            'overrideDefaults' => [
                [
                    'min' => 0,
                    'max' => 1,
                    'maxlength' => 1,
                    'type' => 'select',
                ],
                [
                    'min' => 0,
                    'max' => 1,
                    'maxlength' => 1,
                    'type' => 'select',
                ]
            ],
            'addToFormData' => [
                [
                    'min2' => 0,
                    'max2' => 10,
                    'maxlength2' => 2,
                    'type2' => 'text',
                    'required' => true,
                ],
                [
                    'min' => -128,
                    'min2' => 0,
                    'max' => 127,
                    'max2' => 10,
                    'maxlength' => 3,
                    'maxlength2' => 2,
                    'type' => 'number',
                    'type2' => 'text',
                    'required' => true,
                ]
            ]
        ];
    }
}

// test class
class TestResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    public $apiDataType = 'date';
    public $defaultValidationRules = ['min:-128'];
    public $formData = ['min' => -128];

    protected function setParameterData(): void
    {
        // just setting parameters individually per test
    }
}
