<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\IntResourceParameterInfoProvider;
use Exception;
use Tests\TestCase;

class ResourceParameterInfoProviderTest extends TestCase
{
    protected $parameterAttributeArray = [
        'field' => 'fakeParameterName',
        'type' => 'tinyint',
        'null' => 'no',
        'key' => '',
        'default' => null,
        'extra' => '',
    ];

    /**
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_TestResourceParameterInfoProvider_throws_exception_when_apiDataType_is_not_set(): void
    {

        $this->expectException(Exception::class);
        $this->expectErrorMessage('No apiDataType class property set, this must be set in the child class');

        $testResourceParameterInfoProvider = new TestResourceParameterInfoProvider();

        $testResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);
    }

    /**
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_ResourceParameterInfoProvider_isParameterRequired_sets_data_correctly(): void
    {
        $intResourceParameterInfoProvider = new IntResourceParameterInfoProvider();

        $result = $intResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);
        
        $this->assertEquals(true, $result['formData']['required']);
        $this->assertTrue(in_array('required', $result['defaultValidationRules']));
    }
}

// test class
class TestResourceParameterInfoProvider extends ResourceParameterInfoProvider
{
    protected function getParameterData(): void
    {
        $this->defaultValidationRules = [];
        $this->formData = [];
    }
}
