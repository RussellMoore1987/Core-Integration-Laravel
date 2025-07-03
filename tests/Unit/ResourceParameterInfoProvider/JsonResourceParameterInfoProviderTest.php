<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\JsonResourceParameterInfoProvider;
use Tests\TestCase;

class JsonResourceParameterInfoProviderTest extends TestCase
{
    protected $jsonResourceParameterInfoProvider;
    protected $parameterAttributeArray;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parameterAttributeArray = [
            'field' => 'fakeParameterName',
            'type' => 'json',
            'null' => 'yes',
            'key' => '',
            'default' => null,
            'extra' => '',
        ];

        $this->jsonResourceParameterInfoProvider = new JsonResourceParameterInfoProvider();
    }

    /**
     * @dataProvider jsonResourceParameterInfoProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_JsonResourceParameterInfoProvider_returns_default_values(string $type, array $expectedResultPieces): void
    {
        $this->parameterAttributeArray['type'] = $type;
        $result = $this->jsonResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);

        $this->assertEquals($this->getExpectedResult($expectedResultPieces), $result);
    }

    public function jsonResourceParameterInfoProvider(): array
    {
        return [
            'json' => [
                'json',
                [
                    'type' => 'textarea',
                    'placeholder' => 'Enter valid JSON...',
                    'defaultValidationRules' => [
                        'json',
                    ]
                ]
            ],
        ];
    }

    protected function getExpectedResult(array $expectedResultPieces): array
    {
        return [
            'apiDataType' => 'json',
            'formData' => [
                'type' => $expectedResultPieces['type'],
                'placeholder' => $expectedResultPieces['placeholder'],
            ],
            'defaultValidationRules' => $expectedResultPieces['defaultValidationRules']
        ];
    }
}
