<?php

namespace Tests\Unit\ResourceParameterInfoProvider;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\StringResourceParameterInfoProvider;
use Tests\TestCase;

class StringResourceParameterInfoProviderTest extends TestCase
{
    protected $stringResourceParameterInfoProvider;
    protected $parameterAttributeArray;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parameterAttributeArray = [
            'field' => 'fakeParameterName',
            'type' => 'varchar(255)',
            'null' => 'yes',
            'key' => '',
            'default' => '',
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
            'char' => [
                'char(50)',
                [
                    'maxlength' => 50,
                    'type' => 'text',
                    'defaultValidationRules' => [
                        'string',
                        'max:50',
                    ]
                ]
            ],
            'varchar' => [
                'varchar(100)',
                [
                    'maxlength' => 100,
                    'type' => 'text',
                    'defaultValidationRules' => [
                        'string',
                        'max:100',
                    ]
                ]
            ],
            'tinytext' => [
                'tinytext',
                [
                    'maxlength' => 255,
                    'type' => 'textarea',
                    'defaultValidationRules' => [
                        'string',
                        'max:255',
                    ]
                ]
            ],
            'text' => [
                'text',
                [
                    'maxlength' => 65535,
                    'type' => 'textarea',
                    'defaultValidationRules' => [
                        'string',
                        'max:65535',
                    ]
                ]
            ],
            'mediumtext' => [
                'mediumtext',
                [
                    'maxlength' => 16777215,
                    'type' => 'textarea',
                    'defaultValidationRules' => [
                        'string',
                        'max:16777215',
                    ]
                ]
            ],
            'longtext' => [
                'longtext',
                [
                    'maxlength' => 4294967295,
                    'type' => 'textarea',
                    'defaultValidationRules' => [
                        'string',
                        'max:4294967295',
                    ]
                ]
            ],
            'enum' => [ // TODO: need to test if this is how it comes in from the database
                "enum('active','inactive','pending')",
                [
                    'type' => 'select',
                    'options' => ['active', 'inactive', 'pending'],
                    'defaultValidationRules' => [
                        'string',
                        'in:active,inactive,pending',
                    ]
                ]
            ],
        ];
    }

    protected function getExpectedResult(array $expectedResultPieces): array
    {
        $formData['type'] = $expectedResultPieces['type'];
        if (isset($expectedResultPieces['options'])) {
            $formData['options'] = $expectedResultPieces['options'];
        } else {
            $formData['maxlength'] = $expectedResultPieces['maxlength'];
        }

        return [
            'apiDataType' => 'string',
            'formData' => $formData,
            'defaultValidationRules' => $expectedResultPieces['defaultValidationRules']
        ];
    }
}
