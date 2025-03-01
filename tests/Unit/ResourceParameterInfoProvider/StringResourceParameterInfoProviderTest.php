<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\StringResourceParameterInfoProvider;
use Tests\TestCase;

class StringResourceParameterInfoProviderTest extends TestCase
{
    protected StringResourceParameterInfoProvider $stringResourceParameterInfoProvider;
    protected array $parameterAttributeArray;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parameterAttributeArray = [
            'field' => 'fakeParameterName',
            'type' => 'tinyint',
            'null' => 'no',
            'key' => '',
            'default' => '0',
            'extra' => '',
        ];

        $this->stringResourceParameterInfoProvider = new StringResourceParameterInfoProvider();
    }

    /**
     * @dataProvider stringResourceParameterInfoProvider
     * @group rest
     * @group context
     * @group allRequestMethods
     */
    public function test_StringResourceParameterInfoProvider_returns_default_values(string $type, array $expectedResultPieces): void
    {
        $this->parameterAttributeArray['type'] = $type;
        $result = $this->stringResourceParameterInfoProvider->getData($this->parameterAttributeArray, []);

        $this->assertEquals($this->getExpectedResult($expectedResultPieces), $result);
    }

    public function stringResourceParameterInfoProvider(): array
    {
        return [
            'varchar(50)' => [
                'varchar(50)',
                [
                    'min' => 0,
                    'max' => 50,
                    'maxlength' => 50,
                    'defaultValidationRules' => [
                        'min:0',
                        'max:50',
                    ]
                ]
            ],
        ];
    }

    protected function getExpectedResult(array $expectedResultPieces): array
    {
        return [
            'apiDataType' => 'string',
            'formData' => [
                'min' => $expectedResultPieces['min'],
                'max' => $expectedResultPieces['max'],
                'maxlength' => $expectedResultPieces['maxlength'],
                'type' => 'text',
            ],
            'defaultValidationRules' => [
                'string',
                $expectedResultPieces['defaultValidationRules'][0],
                $expectedResultPieces['defaultValidationRules'][1]
            ]
        ];
    }
}
